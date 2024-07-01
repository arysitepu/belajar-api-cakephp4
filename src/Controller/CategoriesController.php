<?php
declare(strict_types=1);

namespace App\Controller;
use App\Controller\AppController;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\View\JsonView;

class CategoriesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */

     public function initialize(): void
     {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        // $this->getEventManager()->off($this->Csrf);
     }

     public function viewClasses(): array
     {
        return [JsonView::class];
     }

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        
        parent::beforeFilter($event);

        // Nonaktifkan CSRF untuk metode add
        if ($this->getRequest()->getParam('action') === 'add') {
            $csrf = new CsrfProtectionMiddleware();
            $csrf->skipCheckCallback(function ($request) {
                return true; // Skip CSRF check for add action
            });

            $this->getEventManager()->off('Controller.startup', [$csrf, 'checkCsrf']);
            $this->getRequest()->getAttribute('csrf');
        }
    }

    public function index()
    {
       
        $categories = $this->Categories->find('all')->all();
        $this->set('categories', $categories);
        $this->viewBuilder()->setOption('serialize', ['categories']);
        // $categories = $this->paginate($this->Categories);
        // $this->set(compact('categories'));
    }

    /**
     * View method
     *
     * @param string|null $id Category id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $category = $this->Categories->get($id, [
            'contain' => [],
        ]);

        $this->set(compact('category'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {

        $category = $this->Categories->newEmptyEntity();
        if($this->request->is('post')){
            $category = $this->Categories->patchEntity($category, $this->request->getData());
            if ($this->Categories->save($category)) {
                $message = 'Saved';
            } else {
                $message = 'Error';
                $errors = $category->getErrors();
            }
        }
        $this->set([
            'message' => $message,
            'category' => $category,
            'errors' => isset($errors) ? $errors : null,
            '_serialize' => ['message', 'category']
        ]);
        $this->viewBuilder()->setOption('serialize', ['category', 'message', 'errors']);
    }
    

    /**
     * Edit method
     *
     * @param string|null $id Category id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {

        $category = $this->Categories->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $category = $this->Categories->patchEntity($category, $this->request->getData());
            
            if ($this->Categories->save($category)) {
                $message = 'Category updated successfully';
                $errors = null;
            }else{
                $message = 'Error updating category';
                $errors = $category->getErrors();
            }
        }else{
            $message = 'Invalid request method';
            $errors = null;
        }
        $this->set([
            'message' => $message,
            'category' => $category,
            'errors' => isset($errors) ? $errors : null,
            '_serialize' => ['message', 'category']
        ]);
        $this->viewBuilder()->setOption('serialize', ['category', 'message', 'errors']);

        // $category = $this->Categories->get($id, [
        //     'contain' => [],
        // ]);
        // if ($this->request->is(['patch', 'post', 'put'])) {
        //     $category = $this->Categories->patchEntity($category, $this->request->getData());
        //     if ($this->Categories->save($category)) {
        //         $this->Flash->success(__('The category has been saved.'));

        //         return $this->redirect(['action' => 'index']);
        //     }
        //     $this->Flash->error(__('The category could not be saved. Please, try again.'));
        // }
        // $this->set(compact('category'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Category id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $category = $this->Categories->get($id);
        if ($this->Categories->delete($category)) {
            $this->Flash->success(__('The category has been deleted.'));
        } else {
            $this->Flash->error(__('The category could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
