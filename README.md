# Introdução
Este projeto é composto de um CRUD MVC em PHP integrado ao sistema Bitrix através de webhooks, e lida com as entidades Contatos e Empresas. Além de oferecer um event listener para lidar com Negócios Ganhos quando da criação ou atualização da entidde Negócios.

---

# Webhook de Entrada
Foram utilizados webhooks de entrada para tratar as funções do CRUD propriamente ditas, como save, update, delete e find.

As funções do Bitrix utilizadas foram:

Contatos | Empresas | Negócios
:---: | :---: | :---:
crm.contact.add | crm.company.add | crm.deal.get
crm.contact.delete | crm.company.delete 
crm.contact.update | crm.company.update 
crm.contact.get | crm.company.get
crm.contact.list | crm.company.list
crm.contact.company.add | crm.company.contact.add
crm.contact.company.delete | crm.company.contact.delete

# Webhook de Saída
Foram utilizados webhooks de saída para criar o event listener na entidade Negócios. Quando um Negócio vinculado a uma Empresa é criado ou atualizado é feita uma verificação se o Negócio foi ganho ou não. Caso tenha sido ganho, o valor dele é somado aos ganhos da Empresa. 

---

# Estrutura do projeto
O projeto está organizado conforme representado abaixo.

```bash
bitrix-app
│
├───index.php
├───README.md
│
├───controllers
│   └───controller.php
│
├───css
│   └───style.css
│
├───handlers
│   └───deal-handler.php
│
├───models
│   ├───Companies.php
│   ├───Contacts.php
│   ├───Deals.php
│   └───Hook.php
│
└───views
    ├───form.php
    ├───index.php
    ├───list-company.php
    ├───list.php
    └───message.php
```
