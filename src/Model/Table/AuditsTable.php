<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * Audits Model
 *
 * @property \Cake\ORM\Association\BelongsTo $AccessLogs
 * @property \Cake\ORM\Association\HasMany $AuditDeltas
 *
 * @method  \App\Model\Entity\Audit get($primaryKey, $options = [])
 */
class AuditsTable extends Table
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
        $this->getTable('audits');
        $this->getDisplayField('id');
        $this->getPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('CounterCache', ['AccessLogs' => ['access_activity_log_count']]);

        $this->hasMany('AuditDeltas', [
            'foreignKey' => 'audit_id',
            'className' => 'AuditDeltas'
        ]);

        $this->belongsTo('AccessLogs', [
            'foreignKey' => 'source_id',
            'joinType' => 'INNER'
        ]);
    }
}
