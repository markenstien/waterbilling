CREATE VIEW v_user_balance as SELECT SUM(amount) as balance,
customer_id from transactions
GROUP BY customer_id;


create table sms_light(
	id int(10) not null primary key auto_increment,
	total_text int(10),
	date_today date,
	created_at timestamp default now()
);


alter table customers
	add column phone_number varchar(50);