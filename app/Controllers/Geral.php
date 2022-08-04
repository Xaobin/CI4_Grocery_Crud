<?php
namespace App\Controllers;
use App\Libraries\GroceryCrud; 



class Geral extends BaseController
{
//===========================================    
    public function gerenciaClientes(){
	    $crud = new GroceryCrud();

	    $crud->setTable('customers');
        $crud->setTheme('datatables'); //flexigrid
  
        $crud->setLanguage('pt-BR.Portuguese');
	    $crud->unsetPrint();
        $crud->displayAs('customerName','Nome');
        $crud->displayAs('phone','Contato');
        $crud->displayAs('postalCode','CEP');
        $crud->displayAs('contactLastName','Último nome');
        $crud->displayAs('contactFirstName','Primeiro nome');
        $crud->unsetColumns(['addressLine1', 'addressLine2', 'salesRepEmployeeNumber','creditLimit','city','state','country']);




        $output = $crud->render();
		return $this->_saidaOutput($output);
	}
//===========================================
	public function gerenciaOrdens() {
        $crud = new GroceryCrud();

        $crud->setRelation('customerNumber','customers','{contactLastName} {contactFirstName}');
        $crud->setTable('orders');

        $crud->setLanguage('pt-BR.Portuguese');
        $crud->setTheme('datatables');
        $crud->displayAs('customerNumber','Cliente');
        $crud->setSubject('Ordem de entrega');
        $crud->displayAs('comments','Comentário');
        $crud->displayAs('orderDate','Data da Ordem');
        $crud->displayAs('requiredDate','Data da entrega');
        $crud->displayAs('shippedDate','Data do Envio');
        $crud->displayAs('status','Status da entrega');

        $crud->unsetAdd();
        $crud->unsetDelete();

        $output = $crud->render();

        return $this->_saidaOutput($output);
    }
//===========================================
    public function gerenciaAgencias () {
        $crud = new GroceryCrud();
        $crud->setTable('offices');

        $crud->setLanguage('pt-BR.Portuguese');
        $crud->setTheme('datatables');
        $crud->setSubject('Office');
        $crud->requiredFields(['city']);
        $crud->columns(['city','country','phone','addressLine1','postalCode']);
        $crud->displayAs('city','Cidade');
        $crud->displayAs('country','País');
        $crud->displayAs('phone','Contato');
        $crud->displayAs('addressLine1','Endereço');
        $crud->displayAs('postalCode','CEP');
        
        $crud->setRead(); //Botão Visualizar
/* A visualização do formulário (somente leitura) é falsa por padrão.  Para habilitar o botão "Visualizar" em sua grade, você precisará usar a função setRead */
        $output = $crud->render();

        return $this->_saidaOutput($output);
    }
//===========================================
    public function gerenciaProdutos() {
        $crud = new GroceryCrud();

        $crud->setTable('products');
        $crud->setSubject('Product');

        $crud->setLanguage('pt-BR.Portuguese');
        $crud->setTheme('datatables');

        $crud->displayAs('productName','Nome');
        $crud->displayAs('productLine','Linha');
        $crud->displayAs('productScale','escala');
        $crud->displayAs('productVendor','Fornecedor');
        $crud->displayAs('quantityInStock','Estoque');
        $crud->displayAs('buyPrice','Preço');
        $crud->displayAs('productCode','Código');

        $crud->unsetColumns(['productDescription']);
        /* O método callbackColumn é a transformação dos dados de uma coluna no datagrid */
        $crud->callbackColumn('buyPrice', function ($value) {
            return $value.' &euro;';
        });

        $output = $crud->render();

        return $this->_saidaOutput($output);
    }
//===========================================
    public function gerenciaFuncionarios(){
        /*  S E T R E L A T I O N
setRelation(string $fieldName , string $relatedTable, string $relatedTitleField)
setRelation = Uma relação simples de banco de dados entre tabelas é muito comum.  Por exemplo, se quisermos definir uma relação para as tabelas abaixo.

https://www.grocerycrud.com/docs/set-relation
https://www.grocerycrud.com/uploads/documentation/relation-example.png

Para compreender melhor o setRelation - Estas são as tabelas do BD
Tabela employees (employeeNumber(PK), lastName, firstName, extension, email, officeCode(FK?), file_url, jobTitle)
Tabela offices (officeCode(PK),city ,phone ,addressLine1 ,addressLine2 ,state , country,postalCode , territory)
        */
        $crud = new GroceryCrud();

        $crud->setTheme('datatables');
        $crud->setTable('employees');
        $crud->setLanguage('pt-BR.Portuguese');
        /* *** */
        $crud->setRelation('officeCode','offices','city'); /* *** */
        /* *** */
        $crud->setSubject('Employee');

        $crud->displayAs('officeCode','Cidade da Agência');
        $crud->displayAs('lastName','Último nome');
        $crud->displayAs('firstName','Primeiro nome');
        $crud->displayAs('extension','Extensão');
        $crud->displayAs('email','Email');
        $crud->displayAs('file_url','Arquivo');
        $crud->displayAs('jobTitle','Trabalho');

        $crud->requiredFields(['lastName']);

        $output = $crud->render();

        return $this->_saidaOutput($output);
    }
//===========================================
    public function gerenciaFilmes(){
/*  S E T R E L A T I O N .....N to N
setRelationNtoN(
        string $fieldName,
        string $junctionTable,
        string $referrerTable,
        string  $primaryKeyJunctionToCurrent, 
        string $primaryKeyToReferrerTable, 
        string $referrerTitleField
        [, string $sortingFieldName [, array|string $where]]
)

Em bancos de dados relacionais é muito comum ter relacionamento muitos para muitos (também conhecido como n:n ou m:n).  O Grocery CRUD está facilitando para você conectar 3 tabelas e também usá-lo em seu datagrid e formulários.  A sintaxe é fácil e você só precisa adicionar as tabelas e as relações.  Todas as chaves primárias são adicionadas automaticamente para que você não precise.

https://www.grocerycrud.com/docs/set-relation-n-to-n
https://www.grocerycrud.com/uploads/documentation/set-relation-n-to-n.png

TABELAS DO BD
---tabela film (film_id, title, description, release_year, rental_duration, rental_rate, length, replacement_cost, rating, special_features, last_update) 
---tabela film_actor(actor_id, film_id, priority)
---tabela actor(actor_id, fullname, last_update)
---tabela film_category(film_id, category_id)
---tabela category(category_id, name)


*/

        $crud = new GroceryCrud();
        $crud->setLanguage('pt-BR.Portuguese');
        $crud->setTheme('datatables');
        $crud->setTable('film');

        $crud->setRelationNtoN('actors', 'film_actor', 'actor', 'film_id', 'actor_id', 'fullname');

        $crud->setRelationNtoN('category', 'film_category', 'category', 'film_id', 'category_id', 'name');

        $crud->unsetColumns(['special_features','description','actors']);
/* fields(array $fields);
Fields - Este é simplesmente um alias do uso de addFields, editFields, readFields e cloneFields.  Este método foi criado pois é muito comum que no formulário add/edit/read/clone tenha os mesmos campos.*/
        $crud->fields(['title', 'description', 'actors' ,  'category' ,'release_year', 'rental_duration', 'rental_rate', 'length', 'replacement_cost', 'rating', 'special_features']);
        $crud->displayAs('title','Título');
        $crud->displayAs('description','Descrição');
        $crud->displayAs('actors','Atores');
        $crud->displayAs('category','Categoria');
        $crud->displayAs('release_year','Ano');
        $crud->displayAs('rental_duration','Duração do aluguel');
        $crud->displayAs('rental_rate','Taxa de aluguel');
        $crud->displayAs('length','Comprimento');
        $crud->displayAs('replacement_cost','Custo de reposição');
        $crud->displayAs('rating','Avaliação');
        $crud->displayAs('special_features','Características especiais');
        $crud->displayAs('last_update','Última atualização');

        $output = $crud->render();

        return $this->_saidaOutput($output);
    }
    //===========================================
public function dropDown(){
    $crud= new GroceryCrud();
    $crud->setTable('customers');
    //['addressLine1', 'addressLine2', 'salesRepEmployeeNumber','creditLimit','city','state','country']
    $crud->setSubject('Customer', 'Cliente');
$crud->columns(['customerName','phone','addressLine1','creditLimit']);
$crud->setLanguage('pt-BR.Portuguese');

$crud->fieldType('country', 'dropdown', [
    '1' => 'Brazil',
    '2' => 'EUA',
    '3' => 'França',
    '4' => 'Portugal',
    '5' => 'Alemanha',
    '6' => 'Reino Unido',
    '7' => 'Outro'
]);
$crud->fieldType('city', 'dropdown', [
    '1' => 'Manaus',
    '2' => 'Los Angeles',
    '3' => 'San Francisco',
    '4' => 'Porto',
    '5' => 'Belém',
    '6' => 'São Paulo',
    '7' => 'Outro'
]);

    $crud->unsetExport();
    $crud->unsetPrint();
    $crud->displayAs('customerName','Nome');
        $crud->displayAs('phone','Contato');
        $crud->displayAs('postalCode','CEP');
        $crud->displayAs('contactLastName','Último nome');
        $crud->displayAs('contactFirstName','Primeiro nome');
        $crud->displayAs('city','Cidade');
        $crud->displayAs('addressLine1','Endereço linha 1');
        $crud->displayAs('addressLine2','Endereço linha 2');
        $crud->displayAs('creditLimit','Limite de Crédito');
        $crud->displayAs('country','País');
   
   
        $output = $crud->render();
    return $this->_saidaOutput($output);
}
//===========================================
public function textEditor(){
    $crud= new GroceryCrud();
    $crud->setTable('film');
$crud->setSubject('Film', 'Films');
$crud->setTexteditor(['description']);

$output = $crud->render();
return $this->_saidaOutput($output);
}
//===========================================
    private function _saidaOutput($output = null) {
        return view('principal', (array)$output);
    }
//===========================================
}
