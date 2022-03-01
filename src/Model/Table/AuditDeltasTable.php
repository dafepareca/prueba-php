<?php
namespace App\Model\Table;

use App\Model\Entity\AuditDelta;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AuditDeltas Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Audits
 *
 * @method  \App\Model\Entity\Audit get($primaryKey, $options = [])
 */
class AuditDeltasTable extends Table
{
    public $filterArgs = [];
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->getTable('audit_deltas');
        $this->getDisplayField('property_name');
        $this->getPrimaryKey('id');

        $this->addBehavior('CounterCache', [
             'Audits' => ['delta_count']
        ]);

        $this->belongsTo('Audits', [
            'foreignKey' => 'audit_id',
            'joinType' => 'INNER',
            'className' => 'Audits'
        ]);
    }
}
