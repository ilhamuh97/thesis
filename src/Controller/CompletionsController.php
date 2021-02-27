<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Completions Controller
 *
 * @property \App\Model\Table\CompletionsTable $Completions
 *
 * @method \App\Model\Entity\Completion[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CompletionsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $completions = $this->paginate($this->Completions);

        $this->set(compact('completions'));
    }

    /**
     * View method
     *
     * @param string|null $id Completion id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $completion = $this->Completions->get($id, [
            'contain' => ['Products', 'Suggestions'],
        ]);

        $this->set('completion', $completion);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $completion = $this->Completions->newEntity();
        if ($this->request->is('post')) {
            $completion = $this->Completions->patchEntity($completion, $this->request->getData());
            if ($this->Completions->save($completion)) {
                $this->Flash->success(__('The completion has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The completion could not be saved. Please, try again.'));
        }
        $products = $this->Completions->Products->find('list', ['limit' => 200]);
        $suggestions = $this->Completions->Suggestions->find('list', ['limit' => 200]);
        $this->set(compact('completion', 'products', 'suggestions'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Completion id.
     * @return \Cake\Http\Response|null directs on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $completion = $this->Completions->get($id, [
            'contain' => ['Products', 'Suggestions'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $completion = $this->Completions->patchEntity($completion, $this->request->getData());
            if ($this->Completions->save($completion)) {
                $this->Flash->success(__('The completion has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The completion could not be saved. Please, try again.'));
        }
        $products = $this->Completions->Products->find('list', ['limit' => 200]);
        $suggestions = $this->Completions->Suggestions->find('list', ['limit' => 200]);
        $this->set(compact('completion', 'products', 'suggestions'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Completion id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $completion = $this->Completions->get($id);
        if ($this->Completions->delete($completion)) {
            $this->Flash->success(__('The completion has been deleted.'));
        } else {
            $this->Flash->error(__('The completion could not be deleted. Please, try again.'));
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
        $suggest = [];
        $completion_entities = [];
        $product = $this->beautify_product($this->Completions->Products->get($id));
        $completion = $this->Completions->newEntity();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $data['product_type'] = explode(',', $data['product_type']);
            foreach ($data['product_type'] as $product_type) {
                if (!empty($data['selected_attributes']['_ids'])) {
                    $title =  $this->build_completion('attribute', $product_type, $data, $product);
                    $completion_entity['title'] = $title;
                    $completion_entity['products']['_ids'] =  array($id);
                    array_push($completion_entities, $completion_entity);
                } elseif (!empty($data['brand'])) {
                    $title = $data['brand'] . ' ' . $product_type;
                    $completion_entity['title'] = $title;
                    $completion_entity['products']['_ids'] =  array($id);
                    array_push($completion_entities, $completion_entity);
                }
                if (!empty($data['categories']['_ids'])) {
                    $titles =  $this->build_completion('category', $product_type, $data, $product);
                    foreach ($titles as $title) {
                        $completion_entity['title'] = $title;
                        $completion_entity['products']['_ids'] =  array($id);
                        array_push($completion_entities, $completion_entity);
                    }
                }
                /*
                $completion_entity['title'] = ['lala'];
                array_push($completion_entities, $completion_entity);
                */
            }
            // save entities to database
            $patched = $this->Completions->patchEntities($completion, $completion_entities);
            $success = true;
            $saving = true;
            $error_message = "";
            foreach ($patched as $entity) {
                if (!$this->Completions->save($entity)) {
                    $success = false;
                    $saving = false;
                    $error_message = "The completion " . $entity['title'] . " could not be saved. Please, try again.";
                    print_r($error_message);
                    break;
                }
            }
            
            if ($success) {
                $this->Flash->success(__('The completions have been saved.'));
            } else {
                $this->Flash->error(__($error_message));
            }
        }
        $this->set(compact('product'));
    }

    /**
     * Reform Product
     */
    protected function beautify_product($product)
    {
        $localized_aspects = explode(';', $product['localized_aspects']);
        $attributes = $this->decode_localized_aspects($localized_aspects);
        $product['categories'] = explode('|', $product['category']);
        $product['attributes'] = $attributes;
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

    /**
     * build word suggestions
     */
    protected function build_completion($completion_type = null, $product_type, $data, $product)
    {
        switch ($completion_type) {
            case 'attribute':
                //trim selected attributes
                $selected_attributes = [];
                $brand = $data['brand'];
                foreach ($data['selected_attributes']['_ids'] as $attribute_id) {
                    $result = explode(' : ', $product['attributes'][$attribute_id]);
                    $selected_attributes[$result[0]] = $result[1];
                }
                $selected_attributes_combination = '';
                foreach ($selected_attributes as $selected_attribute) {
                    $selected_attributes_combination = $selected_attributes_combination . ' ' . $selected_attribute;
                };
                $suggest['title']=  mb_strtolower($brand . ' ' . $product_type . ' ' . $selected_attributes_combination);
                return $suggest['title'];
                break;
            
            case 'category':
                $selected_categories = [];
                $suggest['titles'] = [];
                foreach ($data['categories']['_ids'] as $category_id) {
                    array_push($selected_categories, $product['categories'][$category_id]);
                }
                foreach ($selected_categories as $category) {
                    $title = $product_type . ' - ' . $category;
                    array_push($suggest['titles'], $title);
                }
                print_r($suggest['titles']);
                return $suggest['titles'];
                break;
            
            default:
                return null;
                break;
        }
        ;
    }
}
