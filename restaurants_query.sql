SELECT DISTINCT tr.*

FROM tbl_restaurants tr

INNER JOIN tbl_items ti
ON tr.`id` = ti.`restaurant_id`

WHERE (tr.`name` LIKE '%king%' OR ti.`name` LIKE '%king%')
AND tr.`is_active` = 1