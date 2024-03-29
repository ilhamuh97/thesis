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
                $conditions[] = array('AND' => array('completions.title LIKE' =>'%'.$search_term.'%'));
            }
        }
        if ($id) {
            $conditions[] = array('AND' => array('completions.id' => $id));
        }

        $completions = $this->Completions->find('all', array(
            'conditions' => $conditions,
        ));
        
        $completions = $this->paginate($completions);

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
            'contain' => ['Products'],
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
        $this->set(compact('completion', 'products'));
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
            'contain' => ['Products'],
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
        $this->set(compact('completion', 'products'));
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
}
