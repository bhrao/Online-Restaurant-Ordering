SELECT `ti`.`name`, `ti`.`price`, `tr`.`name` as res_name

FROM tbl_items ti

INNER JOIN tbl_restaurants tr
ON `tr`.`id` = `ti`.`restaurant_id`

WHERE ti.`name` LIKE '%king%'
AND `ti`.`is_active` = 1

ORDER BY `tr`.`rating`, `tr`.`delivery_time`