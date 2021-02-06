<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Queries Controller
 *
 * @property \App\Model\Table\QueriesTable $Queries
 *
 * @method \App\Model\Entity\Query[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class QueriesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $queries = $this->paginate($this->Queries);

        $this->set(compact('queries'));
    }

    /**
     * View method
     *
     * @param string|null $id Query id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $query = $this->Queries->get($id, [
            'contain' => [],
        ]);

        $this->set('query', $query);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $query = $this->Queries->newEntity();
        if ($this->request->is('post')) {
            $query = $this->Queries->patchEntity($query, $this->request->getData());
            if ($this->Queries->save($query)) {
                $this->Flash->success(__('The query has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The query could not be saved. Please, try again.'));
        }
        $this->set(compact('query'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Query id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $query = $this->Queries->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $query = $this->Queries->patchEntity($query, $this->request->getData());
            if ($this->Queries->save($query)) {
                $this->Flash->success(__('The query has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The query could not be saved. Please, try again.'));
        }
        $this->set(compact('query'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Query id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $query = $this->Queries->get($id);
        if ($this->Queries->delete($query)) {
            $this->Flash->success(__('The query has been deleted.'));
        } else {
            $this->Flash->error(__('The query could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
