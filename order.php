<?php


include "/home/maxlord/public_html/qnits/yiiapp.php";

$order = Order::model()->findByPk(14820);

echo "Order $order->order_id <br/>";
echo "cod $order->cod <br/>";
echo "via_qnits $order->via_qnits <br/>";

echo "current_task_step: {$order->task->step} <br/>";
echo "<br/>";
echo "<br/>";


echo "delta_manager: {$order->delta_manager}";
echo "<br/>";
echo "delta: {$order->delta}";
echo "<br/>";
echo "<br/>";

echo "delta_manager shared(1): {$order->getDelta_manager(1)}";
echo "<br/>";
echo "delta shared(1):: {$order->getDelta(1)}";
echo "<br/>";
echo "<br/>";

echo "delta_manager shared(0): {$order->getDelta_manager(0)}";
echo "<br/>";
echo "delta shared(0):: {$order->getDelta(0)}";
echo "<br/>";
echo "<br/>";

echo "chain_profits: {$order->chain_profits}";
echo "<br/>";
echo "get_chain_profits: {$order->getChain_profits()}";