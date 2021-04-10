<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Products Model
 *
 * @property &\Cake\ORM\Association\BelongsToMany $Completions
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
            ->scalar('brand')
            ->maxLength('brand', 255)
            ->allowEmptyString('brand');

        $validator
            ->scalar('category')
            ->maxLength('category', 255)
            ->allowEmptyString('category');

        $validator
            ->scalar('localized_aspects')
            ->maxLength('localized_aspects', 4294967295)
            ->allowEmptyString('localized_aspects');

        return $validator;
    }

    public function beforeSave($event, $entity, $options)
    {
        if ($entity->completion_columns) {
            // related completion
            $new_completions = $this->_buildCompletions($entity->completion_columns);
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
    protected function _buildCompletions($completion_columns)
    {
        // remove duplication of titles
        $unique = [];
        foreach ($completion_columns as $key=>$item) {
            if (in_array($item['title'], $unique)) {
                unset($completion_columns[$key]);
            } else {
                $unique[] = $item['title'];
            }
        }
        //get the completion titles
        $completion_titles = [];
        foreach ($completion_columns as $column) {
            $completion_titles[] = $column['title'];
        }
        $out = [];
        $query = $this->Completions->find()
            ->where(['Completions.title IN' => $completion_titles]);

        // Remove existing completion titles from the list.
        foreach ($query->extract('title') as $existing) {
            foreach ($completion_columns as $key=>$item) {
                if ($item['title'] == $existing) {
                    unset($completion_columns[$key]);
                }
            }
        }
        // Add existing completions.
        foreach ($query as $completion) {
            $out[] = $completion;
        }
        // Add new completions.
        foreach ($completion_columns as $column) {
            $out[] = $this->Completions->newEntity(['title' => $column['title'], 'type' => $column['type']]);
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
