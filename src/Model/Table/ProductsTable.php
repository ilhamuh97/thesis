<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Products Model
 *
 * @property \App\Model\Table\CategoriesTable&\Cake\ORM\Association\BelongsTo $Categories
 * @property \App\Model\Table\CompletionsTable&\Cake\ORM\Association\BelongsToMany $Completions
 * @property &\Cake\ORM\Association\BelongsToMany $ProductTypes
 *
 * @method \App\Model\Entity\Product get($primaryKey, $options = [])
 * @method \App\Model\Entity\Product newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Product[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Product|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Product saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Product patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Product[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Product findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ProductsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('products');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsToMany('Completions', [
            'foreignKey' => 'product_id',
            'targetForeignKey' => 'completion_id',
            'joinTable' => 'completions_products',
        ]);
        $this->belongsToMany('product_types', [
            'foreignKey' => 'product_id',
            'targetForeignKey' => 'product_type_id',
            'joinTable' => 'product_types_products',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('category_flow')
            ->maxLength('category_flow', 255)
            ->allowEmptyString('category_flow');

        $validator
            ->scalar('inferred_localized_aspects')
            ->allowEmptyString('inferred_localized_aspects');

        return $validator;
    }

    public function beforeSave($event, $entity, $options)
    {
        if ($entity->completion_title) {
            // related completion
            $new_completions = $this->_buildCompletions($entity->completion_title);
            foreach ($new_completions as $completion) {
                array_push($entity->completions, $completion);
            }
        }
        
        if ($entity->product_type_titles) {
            // related completion
            $new_product_type_titles = $this->_buildProductTypes($entity->product_type_titles);
            foreach ($new_product_type_titles as $product_type) {
                array_push($entity->product_types, $product_type);
            }
        }
    }
    protected function _buildCompletions($completion_title)
    {
        // remove duplication
        $completion_title = array_unique($completion_title);
        $out = [];
        $query = $this->Completions->find()
            ->where(['Completions.title IN' => $completion_title]);

        // Remove existing completion titles from the list of new tags.
        foreach ($query->extract('title') as $existing) {
            $index = array_search($existing, $completion_title);
            if ($index !== false) {
                unset($completion_title[$index]);
            }
        }
        // Add existing completion titles.
        foreach ($query as $completion) {
            $out[] = $completion;
        }
        // Add new completion titles.
        foreach ($completion_title as $completion) {
            $out[] = $this->Completions->newEntity(['title' => $completion]);
        }

        return $out;
    }

    protected function _buildProductTypes($product_type_title)
    {
        // remove duplication
        $product_type_title = array_unique($product_type_title);
        $out = [];
        $query = $this->Product_Types->find()
            ->where(['product_types.title IN' => $product_type_title]);

        // Remove existing product type from the list of new tags.
        foreach ($query->extract('title') as $existing) {
            $index = array_search($existing, $product_type_title);
            if ($index !== false) {
                unset($product_type_title[$index]);
            }
        }
        // Add existing product type.
        foreach ($query as $product_type) {
            $out[] = $product_type;
        }
        // Add new product type.
        foreach ($product_type_title as $product_type) {
            $out[] = $this->Product_Types->newEntity(['title' => $product_type]);
        }

        return $out;
    }
}
