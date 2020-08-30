# Backend Laravel

## Autor

Ing. Rubén Pulido

## Scope

Develop a RESTful API that would allow a web or mobile front-end to: 
• Create a contact record. 
• Retrieve a contact record. 
• Update a contact record. 
• Delete a contact record. 
• Search for a record by email or phone number.
• Retrieve all records from the same state or city.

The contact record should represent the following information: name, company, proﬁle image, email, birthdate, phone number (work, personal), and address

## Installation

1) Unzip the contact.zip folder.
2) Add in www or htdocs the folder "api-rest-contact". (Laravel Project)
3) Open and Run the script.sql file. The database used is Mysql.
4) Open the file contact.postman.json file to invoke the endpoints of the laravel backend.

## Test EndPoints

### Country

EndPoint: http://localhost/api-rest-contact/public/contact/countries

Assumption
This api is used in the frontEnd, after backend references this Country code to register the Contact

### States

EndPoint: http://localhost/api-rest-contact/public/contact/states/US

Assumption
This api is used in the frontEnd, after backend references this State code to register the Contact

### Cities

EndPoint: http://localhost/api-rest-contact/public/contact/cities/US/DE

Assumption
This api is used in the frontEnd, after backend references this City code to register the Contact

### Contact Register

EndPoint: http://localhost/api-rest-contact/public/contact/register

Input Parameters

Name of the parameters : image0,json

Content of the parameter

1) image0. Image File. allowed formats(jpg,jpeg,png,gif,jfif).
2) json.  Contact JSON. 
    {"idDocument":"13090757",
    "firstName":"Ruben",
    "middleName":"Alberto",
    "lastName":"Pulido",
    "birthdate":"2020-02-02",
    "phonePersonal":"44080927",
    "phoneWork":"44080927",
    "email":"rubenpul@gmail.com",
    "idCountry":"US",
    "idState":"DE",
    "idCity":"3004373",
    "street":"reverendo padre ansaldo",
    "number":"651",
    "zipCode":"1744",
    "companyName":"Kin+Carta",
    "imageProfile":"none"}
3) Validations
    All the fields are requiered to register the contact, include the image profile

4) Response. JSON Document (Success or Error)

Assumption
The field "idDocument" is the Primary Key.

### Get Contact by State or City

Endpoint: http://localhost/api-rest-contact/public/contact/statecity

Input Parameters

Name of the parameter : json

Content of the parameter

1) {"idState":"DE","idCity":"3099546"}
2) {"idState":"DE"}
3) {"idCity":"3099546"}

Assumption
The Endpoint can response using everyone 1) or 2) or 3) , it depends the case user.

### Get Contact by Email or Phone

Endpoint: http://localhost/api-rest-contact/public/contact/emailphone

Input Parameters

Name of the parameter : json

Content of the parameter

1) {"phonePersonal":"44080927","phoneWork":"44080927","email":"rubenpul@gmail.com"}
2) {"phoneWork":"44080927","email":"rubenpul@gmail.com"}
3) {"email":"rubenpul@gmail.com"}
4) {"phonePersonal":"44080927","phoneWork":"44080927"}
5) {"phonePersonal":"44080927","email":"rubenpul@gmail.com"}

Assumption
The Endpoint can response using everyone 1) or 2) or 3) or 4) or 5) , it depends the case user.

### Get All Contacts

Endpoint: http://localhost/api-rest-contact/public/contacts

### Get a Contact detail

http://localhost/api-rest-contact/public/contact/detail/{id}

Assumption
{id} is the idDocument of the Contact. field database tbl_contact.idDocument

### Get Image Profile Contact

EndPoint: http://localhost/api-rest-contact/public/contact/avatar/1595709800descarga.jfif

Note: Before invoking this endpoint, the contact detail endpoint should be invoked. to get the name of the image "ImageProfile" to consult the Storage of laravel.

### Upload Image Profile

EndPoint: http://localhost/api-rest-contact/public/contact/upload

Name of the parameters : image0

Content of the parameter

1) image0. Image File. allowed formats(jpg,jpeg,png,gif,jfif).


### Delete a Contact detail

EndPoint: http://localhost/api-rest-contact/public/contact/delete/{id}

{id} is the id of the Contact. field database tbl_contact.id

### Update a Contact detail

EndPoint: http://localhost/api-rest-contact/public/contact/update

Name of the parameter : json

Content of the parameter

1) json  
{"idDocument":"13090757","firstName":"Ruben","middleName":"Alberto","lastName":"Pulido","birthdate":"2020-02-02","phonePersonal":"44080927","phoneWork":"44080927","email":"rubenpul@gmail.com","idCountry":"US","idState":"DE","idCity":"3004373","street":"reverendo padre ansaldo","number":"651","zipCode":"1744","companyName":"Kin+Carta","imageProfile":"none"}

Assumptions

1) User can update one or all the fields of the json document. 
2) if the user desires update the image profile, after the response 200, must invoke Endpoint http://localhost/api-rest-contact/public/contact/updateimage. The user must retrieve the field "imageProfile" and select the new image.


### Update Image Profile Contact

EndPoint: http://localhost/api-rest-contact/public/contact/updateimage

Name of the parameter : imageNew, imageOld

Content of the parameter

1) ImageNew. Image File. allowed formats(jpg,jpeg,png,gif,jfif).
2) ImageOld. String. 

Assumption

After invoke contact detail, user should send the content of "ImageProfile" in this field.

## Unit Test

End Point Contact Register 

1) Open a Terminal (VSCode) or SO, and locate the directory of the project api-rest-contact
2) Execute ./vendor/bin/phpunit
3) The Test file is  tests/Feature/RegisterContactTest.php

Assumptions
1) It runs succesfully the first time, the second time you must change the content of the idDocument since it is a primary key, on the contrary it will give an error.


## Development tools
1) VSCode 1.47.2
2) Laravel Framework 7.21.0
3) Postman v7.29.1
4) Xampp v3.2.4
5) WorkBench Mysql 8.0
6) DataBase MySql.



## References

- **[Api Countries States Cities](https://rapidapi.com/wirefreethought/api/geodb-cities)**

