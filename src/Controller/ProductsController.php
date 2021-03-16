<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Products Controller
 *
 * @property \App\Model\Table\ProductsTable $Products
 *
 * @method \App\Model\Entity\Product[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProductsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index($title= null)
    {
        if (isset($this->request->query['input_title'])) {
            $title = $this->request->query['input_title'];
        }
        $conditions = array();
        $search_terms = explode(' ', $title);
        foreach($search_terms as $search_term){
            $conditions[] = array('AND' => array('Products.title LIKE' =>'%'.$search_term.'%'));
        }
        $products = $this->Products->find('all', array(
            'conditions' => $conditions,
            'contain' => ['Completions','Product_types'],
        ));
        $products = $this->paginate($products); 
        
        $this->set(compact('products'));
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function auto()
    {
        $products = $this->Products->find('all');

        if ($this->request->is(['patch', 'post', 'put'])) {
            // get whole sent data
            $data = $this->request->getData();
            // TODO: get recommended Attributes based on category
            $recommended_attributes = ['farbe', 'größe'];
            foreach ($data['product_types']['_ids'] as $product_type_id) {
                // get product type title
                $product_type_title = $this->Products->Product_Types->get($product_type_id)->title;
                // get all products based on product type
                $search_terms = explode(' ', $product_type_title);
                foreach($search_terms as $search_term){
                    $conditions[] = array('AND' => array('Products.title LIKE' =>'%'.$search_term.'%'));
                }
                $products = $this->Products->find('all', array(
                    'conditions' => $conditions,
                    'contain' => ['Completions','Product_types'],
                ));
                // remove non alphabetic symbols
                $product_type_title = preg_replace("/[^A-Za-z0-9öÖäAüÜ \_]/", '', $product_type_title);
                $product_type_title = trim($product_type_title);
                // loop every products
                foreach ($products as $product) {
                    // don't remove the owned product types
                    $completion_entities['product_types']['_ids'] = [];
                    if ($product->product_types) {
                        $ids = [];
                        foreach ($product->product_types as $related_product_type) {
                            array_push($ids, $related_product_type->id);
                        }
                        if (!in_array($product_type_id, $ids)) {
                            array_push($ids, $product_type_id);
                        }
                        $completion_entities['product_types']['_ids'] = $ids;
                    } else {
                        $ids = [];
                        array_push($ids, $product_type_id);
                        $completion_entities['product_types']['_ids'] = $ids;
                    }
                    // don't remove the owned completions
                    $completion_entities['completions']['_ids'] = [];
                    if ($product->completions) {
                        $ids = [];
                        foreach ($product->completions as $related_completion) {
                            array_push($ids, $related_completion->id);
                        }
                        $completion_entities['completions']['_ids'] = $ids;
                    }
                    // decode localized aspects, make categories as an array, and unset unneccessary
                    $readable_product = $this->beautify_product($product);
                    $completion_entities['completions_title'] = []; //declare empty completions_enitities
                    // get attributes compared to recommended atts
                    $selected_attributes = [];
                    $selected_attributes_combinations = [];
                    foreach ($readable_product['attributes'] as $attribute) {
                        $result = explode(' : ', $attribute);
                        $key = $result[0];
                        $value = $result[1];
                        if (in_array(mb_strtolower($key), $recommended_attributes)) {
                            if (str_contains($value, '/')) {
                                $selected_attributes[$key] = explode('/', $value);
                            } else {
                                $selected_attributes[$key] = array($value);
                            }
                        }
                    }
                    // get combinations min 1 and max 3
                    if ($selected_attributes) {
                        $combinations = $this->combinations($selected_attributes, 1, 3);
                        foreach ($combinations as $combination) {
                            // get permuations
                            $permutations = $this->permutations($combination);
                            foreach ($permutations as $p) {
                                foreach ($this->array_cartesian_product($p) as $result) {
                                    $selected_attributes_combinations = join(' ', $result);
                                    // remove multiple whitespaces, str to lower, and remove whitescpace before and after sentence
                                    $end_combination = trim(preg_replace('/\s+/', ' ', mb_strtolower($product_type_title . ' ' .$selected_attributes_combinations)));
                                    array_push($completion_entities['completions_title'], $end_combination);
                                }
                            }
                        }
                    }
                    foreach ($readable_product['categories'] as $category) {
                        $title =  mb_strtolower($product_type_title . ' - ' . $category);
                        array_push($completion_entities['completions_title'], $title);
                    }
                    // print_r($completion_entities['completions_title']);
                    $product = $this->Products->patchEntity($product, $completion_entities);
                    if ($this->Products->save($product)) {
                        $this->Flash->success(__('The product has been saved.'));
                    } else {
                        $this->Flash->error(__('The product could not be saved. Please, try again.'));
                    }
                }
            }
        }
        $product_types = $this->Products->Product_Types->find('list', ['limit' => 200]);
        $products = $this->paginate($products);
        $this->set(compact('products', 'product_types'));
    }


    /**
     * View method
     *
     * @param string|null $id Product id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $product = $this->Products->get($id, [
            'contain' => ['Completions', 'Product_types'],
        ]);

        $this->set('product', $product);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $product = $this->Products->newEntity();
        if ($this->request->is('post')) {
            $product = $this->Products->patchEntity($product, $this->request->getData());
            if ($this->Products->save($product)) {
                $this->Flash->success(__('The product has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product could not be saved. Please, try again.'));
        }
        $completions = $this->Products->Completions->find('list', ['limit' => 200]);
        $this->set(compact('product', 'completions'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Product id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $product = $this->Products->get($id, [
            'contain' => ['Completions'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $product = $this->Products->patchEntity($product, $this->request->getData());
            if ($this->Products->save($product)) {
                $this->Flash->success(__('The product has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product could not be saved. Please, try again.'));
        }
        $completions = $this->Products->Completions->find('list', ['limit' => 200]);
        $this->set(compact('product', 'completions'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Product id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $product = $this->Products->get($id);
        if ($this->Products->delete($product)) {
            $this->Flash->success(__('The product has been deleted.'));
        } else {
            $this->Flash->error(__('The product could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Generate Word Suggestion
     *
     * @param string|null $id Product id
     */
    public function generate($id = null)
    {
        $product = $this->Products->get($id, [
            'contain' => ['Completions', 'Product_types'],
        ]);
        $readable_product = $this->beautify_product($this->Products->get($id));
        $completion_entities['completions_title'] = [];
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $related_completions = $data['completions'];
            $related_product_types = $data['product_types'];
            foreach ($data['product_types']['_ids'] as $product_type_id) {
                // remove all non alphabet numeric type
                $product_type = $this->Products->Product_Types->get($product_type_id)->title;
                // print_r($product_type);
                $product_type = preg_replace("/[^A-Za-z0-9öÖäAüÜ \_]/", '', $product_type);
                $product_type = trim($product_type);
                if ($data['selected_attributes']['_ids']) {
                    // attribute + brand
                    $titles =  $this->build_completion($product_type, $data, $readable_product, 'attributes');
                    foreach ($titles as $title) {
                        array_push($completion_entities['completions_title'], $title);
                    }
                } elseif (!empty($data['brand'])) {
                    // only brand
                    $title = mb_strtolower($product_type . ' ' .  $data['brand']);
                    array_push($completion_entities['completions_title'], $title);
                }
                if ($data['categories']['_ids']) {
                    // categories "(Product type) - (category)"
                    $titles =  $this->build_completion($product_type, $data, $readable_product, 'categories');
                    foreach ($titles as $title) {
                        array_push($completion_entities['completions_title'], $title);
                    }
                }
                array_push($completion_entities['completions_title'], $product_type);
            }
            $completion_entities['product_types'] = $related_product_types;
            $completion_entities['completions'] = $related_completions;
            $product = $this->Products->patchEntity($product, $completion_entities);
            //save completions
            if ($this->Products->save($product)) {
                $this->Flash->success(__('The product has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product could not be saved. Please, try again.'));
        }
        $completions = $this->Products->Completions->find('list', ['limit' => 200]);
        $product_types = $this->Products->Product_Types->find('list', ['limit' => 200]);
        $this->set(compact('product', 'readable_product', 'completions', 'product_types'));
    }

    /**
    * Reform Product
    */
    protected function beautify_product($product)
    {
        $localized_aspects = explode(';', $product['localized_aspects']);
        $attributes = $this->decode_localized_aspects($localized_aspects);
        $product['categories'] = explode('|', $product['category']);
        $product['attributes'] = $this->neglect($attributes, 'attributes');
        $product['brand'] = $this->neglect($product['brand'], 'brand');
        //remove unneccessary
        unset($product['localized_aspects']);
        unset($product['category']);
        return $product;
    }

    /**
     * Decode localaized_aspects to readable attributes
     */
    protected function decode_localized_aspects($localized_aspects)
    {
        $attributes = [];
        $index = 0;
        foreach ($localized_aspects as $l_a) {
            $result = explode(':', $l_a);
            if ($result[0] && $result[1]) {
                $attributes[$index] = base64_decode($result[0]) . ' : ' . base64_decode($result[1]);
            }
            $index++;
        }
        return $attributes;
    }

    protected function neglect($item, $type)
    {
        $neglections = ["nicht zutreffend", "unbekannt", "markenlos", null, "nein"];
        switch ($type) {
            case 'attributes':
                $result = [];
                foreach ($item as $i) {
                    $value = explode(' : ', $i);
                    if (!in_array(mb_strtolower($value[1]), $neglections)) {
                        array_push($result, $i);
                    }
                }
                return $result;
                break;
            
            case 'brand':
                $result = "";
                if (!in_array(mb_strtolower($item), $neglections)) {
                    $result = $item;
                }
                return $result;
                break;
            
            default:
                return $item;
                break;
        }
    }

    /**
     * build word suggestions
     */
    protected function build_completion($product_type, $data, $product, $completion_type = null)
    {
        switch ($completion_type) {
            case 'attributes':
                $selected_attributes = [];
                $suggest['titles'] = [];
                // trim selected attributes
                foreach ($data['selected_attributes']['_ids'] as $attribute_id) {
                    $result = explode(' : ', $product['attributes'][$attribute_id]);
                    if (str_contains($result[1], '/')) {
                        $selected_attributes[$result[0]] = explode('/', $result[1]);
                    } else {
                        $selected_attributes[$result[0]] = array($result[1]);
                    }
                }
                if ($data['brand']) {
                    $selected_attributes['brand'] = array($data['brand']);
                }
                // get combinations min 1 and max 3
                $combinations = $this->combinations($selected_attributes, 1, 3);
                sort($combinations);
                foreach ($combinations as $combination) {
                    // get permuations
                    $permutations = $this->permutations($combination);
                    foreach ($permutations as $p) {
                        foreach ($this->array_cartesian_product($p) as $result) {
                            $selected_attributes_combinations = join(' ', $result);
                            $end_combination = mb_strtolower($product_type . ' ' .$selected_attributes_combinations);
                            array_push($suggest['titles'], $end_combination);
                        }
                    }
                }
                return $suggest['titles'];
                break;
            
            case 'categories':
                $suggest['titles'] = [];
                // trim categories
                foreach ($data['categories']['_ids'] as $category_id) {
                    $title =  mb_strtolower($product_type . ' - ' . $product['categories'][$category_id]);
                    array_push($suggest['titles'], $title);
                }
                return $suggest['titles'];
                break;
            
            default:
                return null;
                break;
        };
    }

    /**
     * do cartesian formula
     * https://stackoverflow.com/a/8567479
     */
    protected function array_cartesian_product($arrays)
    {
        $result = array();
        $arrays = array_values($arrays);
        $sizeIn = sizeof($arrays);
        $size = $sizeIn > 0 ? 1 : 0;
        foreach ($arrays as $array) {
            $size = $size * sizeof($array);
        }
        for ($i = 0; $i < $size; $i ++) {
            $result[$i] = array();
            for ($j = 0; $j < $sizeIn; $j ++) {
                array_push($result[$i], current($arrays[$j]));
            }
            for ($j = ($sizeIn -1); $j >= 0; $j --) {
                if (next($arrays[$j])) {
                    break;
                } elseif (isset($arrays[$j])) {
                    reset($arrays[$j]);
                }
            }
        }
        return $result;
    }


    /**
     * do permuation formula
     * https://stackoverflow.com/a/12749950
     */
    protected function permutations($InArray, &$ReturnArray = array(), $InProcessedArray = array())
    {
        if (count($InArray) == 1) {
            $ReturnArray[] = array_merge($InProcessedArray, $InArray);
        } else {
            foreach ($InArray as $Key=>$value) {
                $CopyArray = $InArray;
                unset($CopyArray[$Key]);
                $this->permutations($CopyArray, $ReturnArray, array_merge($InProcessedArray, array($Key=>$value)));
            }
        }
        return $ReturnArray;
    }

    /**
     * do combination formula
     * https://stackoverflow.com/a/65061503
     */
    protected function combinations($values, $minLength = 1, $maxLength = 2000)
    {
        $count = count($values);
        $size = pow(2, $count);
        $keys = array_keys($values);
        $return = [];

        for ($i = 0; $i < $size; $i ++) {
            $b = sprintf("%0" . $count . "b", $i);
            $out = [];

            for ($j = 0; $j < $count; $j ++) {
                if ($b[$j] == '1') {
                    $out[$keys[$j]] = $values[$keys[$j]];
                }
            }

            if (count($out) >= $minLength && count($out) <= $maxLength) {
                $return[] = $out;
            }
        }
        return $return;
    }
}
