```bash
composer install
bin/console doctrine:migrations:migrate -n
bin/console hautelook:fixtures:load --purge-with-truncate -n
bin/console fos:elastica:populate
bin/console app:add-offer <product-id> <offer-price> <company-name>
bin/console app:sync-index

```
