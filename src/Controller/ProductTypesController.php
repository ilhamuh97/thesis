<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ProductTypes Controller
 *
 * @property \App\Model\Table\ProductTypesTable $ProductTypes
 *
 * @method \App\Model\Entity\ProductType[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProductTypesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index($title= null, $id=null)
    {
        if (isset($this->request->query['input_title'])) {
            $title = $this->request->query['input_title'];
        }

        if (isset($this->request->query['input_id'])) {
            $id = $this->request->query['input_id'];
        }
        $conditions = array();
        if ($title) {
            $search_terms = explode(' ', $title);
            foreach ($search_terms as $search_term) {
                $conditions[] = array('AND' => array('ProductTypes.title LIKE' =>'%'.$search_term.'%'));
            }
        }
        if ($id) {
            $conditions[] = array('AND' => array('ProductTypes.id' => $id));
        }
        $productTypes = $this->ProductTypes->find('all', array(
            'conditions' => $conditions,
        ));
        
        $productTypes = $this->paginate($productTypes);

        $this->set(compact('productTypes'));
    }

    /**
     * View method
     *
     * @param string|null $id Product Type id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $productType = $this->ProductTypes->get($id, [
            'contain' => ['products'],
        ]);

        $this->set('productType', $productType);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $productType = $this->ProductTypes->newEntity();
        if ($this->request->is('post')) {
            $productType = $this->ProductTypes->patchEntity($productType, $this->request->getData());
            if ($this->ProductTypes->save($productType)) {
                $this->Flash->success(__('The product type has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product type could not be saved. Please, try again.'));
        }
        $products = $this->ProductTypes->products->find('list', ['limit' => 200]);
        $this->set(compact('productType', 'products'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Product Type id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $productType = $this->ProductTypes->get($id, [
            'contain' => ['products'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $productType = $this->ProductTypes->patchEntity($productType, $this->request->getData());
            if ($this->ProductTypes->save($productType)) {
                $this->Flash->success(__('The product type has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product type could not be saved. Please, try again.'));
        }
        $products = $this->ProductTypes->products->find('list', ['limit' => 200]);
        $this->set(compact('productType', 'products'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Product Type id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $productType = $this->ProductTypes->get($id);
        if ($this->ProductTypes->delete($productType)) {
            $this->Flash->success(__('The product type has been deleted.'));
        } else {
            $this->Flash->error(__('The product type could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
