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
    public function index()
    {
        $products = $this->paginate($this->Products);

        $this->set(compact('products'));
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
            'contain' => ['Completions'],
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
            'contain' => ['Completions'],
        ]);
        $readable_product = $this->beautify_product($this->Products->get($id));
        $completion_entities['completions_title'] = [];
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $related_completions = $data['completions'];
            $data['product_type'] = explode(',', $data['product_type']);
            foreach ($data['product_type'] as $product_type) {
                //replace all non alphabet numeric type
                $product_type = preg_replace("/[^A-Za-z0-9öÖäAüÜ \_]/", '', $product_type);
                if (!empty($data['selected_attributes']['_ids'])) {
                    //attribute + brand (brand added in front of the completion)
                    $title =  $this->build_completion($product_type, $data, $readable_product, 'attributes');
                    array_push($completion_entities['completions_title'], $title);
                } elseif (!empty($data['brand'])) {
                    // only brand
                    $title = mb_strtolower($data['brand'] . ' ' . $product_type);
                    array_push($completion_entities['completions_title'], $title);
                }
                if (!empty($data['categories']['_ids'])) {
                    $titles =  $this->build_completion($product_type, $data, $readable_product, 'categories');
                    foreach ($titles as $title) {
                        array_push($completion_entities['completions_title'], $title);
                    }
                }
                $completion_entities['completions'] = $related_completions;
                $product = $this->Products->patchEntity($product, $completion_entities);
                if ($this->Products->save($product)) {
                    $this->Flash->success(__('The product has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The product could not be saved. Please, try again.'));
            }
        }
        $completions = $this->Products->Completions->find('list', ['limit' => 200]);
        $this->set(compact('product', 'readable_product', 'completions'));
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
            $attributes[$index] = base64_decode($result[0]) . ' : ' . base64_decode($result[1]);
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
                //trim selected attributes
                $selected_attributes = [];
                $brand = $data['brand'];
                foreach ($data['selected_attributes']['_ids'] as $attribute_id) {
                    $result = explode(' : ', $product['attributes'][$attribute_id]);
                    $selected_attributes[$result[0]] = $result[1];
                }
                $selected_attributes_combination = '';
                //combine selected attributes
                foreach ($selected_attributes as $selected_attribute) {
                    $selected_attributes_combination = $selected_attributes_combination . ' ' . $selected_attribute;
                };
                //combine all (brand, product type, selectede attribtues)
                $suggest['title']=  mb_strtolower($brand . ' ' . $product_type . $selected_attributes_combination);
                return $suggest['title'];
                break;
            
            case 'categories':
                $suggest['titles'] = [];
                //trim categories
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
}
