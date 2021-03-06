<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Completions Model
 *
 * @property \App\Model\Table\ProductsTable&\Cake\ORM\Association\BelongsToMany $Products
 * @property \App\Model\Table\SuggestionsTable&\Cake\ORM\Association\BelongsToMany $Suggestions
 *
 * @method \App\Model\Entity\Completion get($primaryKey, $options = [])
 * @method \App\Model\Entity\Completion newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Completion[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Completion|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Completion saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Completion patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Completion[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Completion findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CompletionsTable extends Table
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

        $this->setTable('completions');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsToMany('Products', [
            'foreignKey' => 'completion_id',
            'targetForeignKey' => 'product_id',
            'joinTable' => 'completions_products',
        ]);
        $this->belongsToMany('Suggestions', [
            'foreignKey' => 'completion_id',
            'targetForeignKey' => 'suggestion_id',
            'joinTable' => 'completions_suggestions',
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

        return $validator;
    }

    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['title']));

        return $rules;
    }

}
