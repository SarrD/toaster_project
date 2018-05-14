for i in `ls src/AppBundle/Entity/*.php`
do
entite=${i##*/}
entite=${entite%%.php}
echo "suppression de src/AppBundle/Controller/$entiteController.php"
rm -rf "src/AppBundle/Controller/${entite}Controller.php"
done
rm -fr src/AppBundle/Entity
rm -fr src/AppBundle/Admin
rm -fr src/AppBundle/Form
rm -fr src/AppBundle/Resources/config/doctrine
php bin/console doctrine:mapping:import --force AppBundle xml
php bin/console cache:clear
php bin/console doctrine:mapping:convert annotation ./src
php bin/console doctrine:generate:entities AppBundle
for i in `ls src/AppBundle/Entity/*.php`
do
entite=${i##*/}
entite=${entite%%.php}

php bin/console sonata:admin:generate -n AppBundle/Entity/${entite}
done
