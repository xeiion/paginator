# paginator

$paginate = new Paginate($db);

$paginate->query('SELECT * FROM table');

$paginate->AddClass('class');
$paginate->AddClass(array('class','class1');

$paginate->setCurrentPage('1);

$paginate->setMaxPage('1');

echo $paginate->GenerateNavi();

$paginate->Result();
