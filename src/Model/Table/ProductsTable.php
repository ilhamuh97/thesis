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
        if ($entity->completions_title) {
            // related completion
            $related_completions = $entity->completions;
            foreach ($related_completions as $ec) {
                array_push($entity->completions_title, $ec->title);
            }
            $entity->completions = $this->_buildCompletions($entity->completions_title);
        }
    }
    protected function _buildCompletions($completions_title)
    {
        // remove duplication
        $completions_title = array_unique($completions_title);
        $out = [];
        $query = $this->Completions->find()
            ->where(['Completions.title IN' => $completions_title]);


        // Remove existing tags from the list of new tags.
        foreach ($query->extract('title') as $existing) {
            $index = array_search($existing, $completions_title);
            if ($index !== false) {
                unset($completions_title[$index]);
            }
        }
        // Add existing tags.
        foreach ($query as $completion) {
            $out[] = $completion;
        }
        // Add new tags.
        foreach ($completions_title as $completion) {
            $out[] = $this->Completions->newEntity(['title' => $completion]);
        }

        return $out;
    }
}
