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
    }