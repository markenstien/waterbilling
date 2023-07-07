<?php
    use Services\TransactionService;
    load(['TransactionService'], SERVICES);

    class TransactionModel extends Model
    {
        public $table = 'transactions';
        public $_fillables = [
            'user_id',
            'platform_id',
            'container_id',
            'customer_id',
            'amount',
            'parent_id',
            'parent_key'
        ];

        public function pickUpOrDelivery($containerData, $actionTaken)
        {
            $containerMovementModel = model('ContainerMovementModel');
            $commonEntryData = [
                'user_id' => $containerData->cx_id,
                'platform_id' => $containerData->platform_id,
                'container_id' => $containerData->container_id,
                'customer_id' => $containerData->customer_id
            ];
            $movementData = array_merge($commonEntryData, [
                'entry_type' => $actionTaken
            ]);
            $movementId = $containerMovementModel->store($movementData);
            if (isEqual($actionTaken,'delivery')) {
                $commonEntryData['amount'] = amountConvert(25, 'DEDUCT');
                $commonEntryData['parent_id'] = $movementId;
                $commonEntryData['parent_key'] = TransactionService::SERVICE_DELIVERY;
                return parent::createOrUpdate($commonEntryData);
            }
            return $movementId;
        }

        public function getLastLogByContainer($containerId) {
            return parent::single([
                'container_id' => $containerId,
            ],'*','id desc');
        }

        public function getTotalByCustomer($customerId) {
            $this->db->query(
                "SELECT SUM(amount) as total_amount
                    FROM transactions
                WHERE customer_id = '{$customerId}' "
            );
            return $this->db->single()->total_amount ?? 0;
        }

        public function getAll($params = []) {
            $where = null;
            $order = null;
            $limit = null;

            if(!empty($params['where'])) {
                $where = " WHERE ".parent::conditionConvert($params['where']);
            }

            if(!empty($params['order'])) {
                $order = " ORDER BY {$params['order']} ";
            }

            if(!empty($params['limit'])) {
                $limit = " LIMIT {$params['limit']} ";
            }

            $this->db->query(
                "SELECT transaction.*,
                    customer.full_name as customer_name,
                    platform.platform_name as platform_name,
                    concat(user.firstname, ' ',user.lastname) as staff_name

                    FROM {$this->table} as transaction 
                    LEFT JOIN users as user 
                        ON user.id = transaction.user_id
                    LEFT JOIN customers as customer 
                        ON customer.id = transaction.customer_id
                    LEFT JOIN platforms as platform
                        ON platform.id = transaction.platform_id

                    {$where} {$order} {$limit}"
            );

            return $this->db->resultSet();
        }
    }