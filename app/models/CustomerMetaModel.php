<?php
    class CustomerMetaModel extends Model
    {
        public $table = 'customer_meta';

        public function savePoint($userId, $point){
            $meta = $this->getMeta($userId);
            $totalPoint = $meta->points += $point;
            $res = parent::update([
                'points' => $totalPoint
            ], ['customer_id' => $userId]);

            if($res) {
                $message = $point > 0 ? "Point added " : "Point deduct";
                $this->addMessage("{$message} ($point), Current Point {$totalPoint}");
            }else{
                $this->addError("Something went wrong.");
            }
            return true;
        }

        public function getMeta($userId) {
            $meta = parent::single([
                'customer_id' => $userId
            ]);

            if(!$meta) {
                parent::store([
                    'customer_id' => $userId,
                    'points' => 0,
                    'topup_amount' => 0
                ]);

                $meta = parent::single([
                    'customer_id' => $userId
                ]);
            }

            return $meta;
        }
    }