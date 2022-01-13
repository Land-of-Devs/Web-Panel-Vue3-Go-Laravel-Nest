# Web Panel
Creators: 

- [Fco. Javier Diez Garcia](https://github.com/JDiezGarcia)
- [Ivan Ferrer](https://github.com/iferrer20)
- [Juan Jose Paya](https://github.com/jjpaya)
  

Type: CFGS Proyect

School: IES L'Estacio

Degree: Web Applications Development

# Index
- [Web Panel](#web-panel)
- [Index](#index)
- [Introduction](#introduction)
- [Installation](#installation)
- [Content](#content)
  - [Navbar](#navbar)
  - [Home](#home)
  - [Shop](#shop)
  - [Panel](#panel)
    - [DASHBOARD](#dashboard)
    - [TICKETS](#tickets)
    - [PRODUCTS](#products)
    - [USERS](#users)
    - [ADMIN ACCESS](#admin-access)
- [Technologies](#technologies)

# Introduction

What is Web Panel?

It is a versatile platform with the power of managing tickets, products, users and their roles. Our team achieved an application to manage any kind of types mentioned before, making it the most reusable as possible. The platform can adjust to your necesities with minimun changes in the code.

# Installation
Here you have a link with the installation tutorial: 
[INSTALLATION](./INSTALLING.md#webpanel-install-and-configuration-steps).

# Content

## Navbar
| SECTION | FEATURES |
| - | - |
| Menu |  Nav menu with all the pages. It will show if you are logged into the web, if you aren't, only Home will be shown.|
| Name | Name of the website. |
| Tickets | Button to open a modal with forms to send tickets, you need to be logged to see and send them ( update user and create new product ). |
| Login | You have to click login or click sign up.If you register your account, a toast will show telling you than email was sent, if you try login and you aren't verified, the modal will inform you and we will send another mail, this section will disappear once your logged and it will show the logout button.|
> This section is the same in all the pages, except on Panel that will disappear
***
## Home
| SECTION | FEATURES |
| - | - |
| Carousel | It has some images to have a nice welcome page. |
***
## Shop
| Section | Features |
| - | - |
| Product List | A list of all the products that have status on complete, each product have 2 buttons, one to report it and send a ticket, and other to view its details. |
| Pagination | The pagination will navigate you from page to page. |
| Details | A modal with the details of the product. |
***
## Panel
| Section | Features |
| - | - |
| Sidebar | This has all sections of the panel, hiding Users if the user don't have the role Admin, if you click on it without access but having the role it will show Admin Access. We have a button to go back to the store.|
| Layout | This will show the section that we select. |
> This section is the same in all the sections of the panel changing only the layout

### DASHBOARD
| Section | Features |
| - | - |
| Date Selector | We can select the range of dates to show the statistics.|
| Admin Button | If you're an admin and you need to have access again to all statistics, this button will redirect you to Admin Access to send the verification code and return with admin access.
| Creation Statistics | You will have only tickets if your not an admin or if you don't have admin access in that moment, if you have admin access you will see products and user creation stadistics, depending of the date selector data. |
| Year Selector | A selector for year stadistics, only for admins. |
| Total Stadistics | You will see a total of users and products per year, only for admins. |
***
### TICKETS
| Section | Features |
| - | - |
| Selectors | We have two, the first one to select the status of the ticket and other one to select the type of the ticket, both can work together. |
| Table | Table with 10 tickets per page showing some information per row. |
| Cells | Checkable box, title, badge with type, name of the user that sent the ticket, badge with the status, and actions. |
| Selected Items | When you select a ticket or tickets it will show one selector with 2 options delete and status. If the second one is selected new selector will appear with values to change, in both cases when you select on any option ( if it have values you will need to select one) a confirm selection and a button to send the changes will appear, only if we press confirm the sent button will be enabled to sent. |
| User Cells | If there is a user you can click on the button and it will show his user info with a modal. |
| Action Cells | It will show two buttons, the first one to show his info with a modal, and depending of the type, the section content will change to show that kind of type info. The other button to delete the ticket, all panel user (Staff, Admins) can do all actions.|
| Pagination | To change to any page, you can go first to last, previous to next and you can type a specific page. |
***
### PRODUCTS
| Section | Features |
| - | - |
| Tabs | We have two, one for all products and other one for your products. |
| Selectors | One to select the status of the product. |
| Create Button | We have a button to open a modal with product creation form, you need to fill all data, including one image. | 
| Table | Table with 10 products per page showing some information per row. |
| Cells | Checkable box, small image of the product, name of the creator, price, badge with the status, and actions. |
| Selected Items | When you select a product or products, it will show one selector with 2 options delete and status. If the second one is selected new selector will appear with values to change, in both cases when you select on any option ( if it have values you will need to select one) a confirm selection and a button to send the changes will appear, only if we press confirm the sent button will be enabled to sent. This section will be hidden, instead we will show Admin Access button because this options are only available to admins. |
| User Cells | If there is a user you can click on the button and it will show his user info with a modal. |
| Action Cells | It will show two buttons, the first one to show his info with a modal and depending if you are the creator or not, the card will show a button to edit that product, admins have permission to change any product. The other button to delete the product, only Admins can delete.|
| Pagination | To change to any page, you can go first to last, previous to next and you can type a specific page. |
***
### USERS
| Section | Features |
| - | - |
| Search | We can search user by email. It will be showing in the table all matches meanwhile we are typing. |
| Create Button | We have a button to open a modal with user creation form, you need to fill all data, you can upload or not, one image, if the user is given role Admin it will send an email with the Admin Access code for Google Authenticator. | 
| Table | Table with 10 users per page showing some information per row. |
| Cells | Checkable box, avatar , name , hash, badge role, verify with and 'x' or a 'green tick' and actions. |
| Selected Items | When you select a user or users it will show one selector with 2 options delete and verify, in both cases when you select on any option a confirm selection and a button to send the changes will appear, only if we press confirm it will be sent.|
| Hash Cells | It will show a hash with at least 4 digits |
| Action Cells | It will show two buttons, the first one to show his info with a modal, the card will show a button to edit that user, and the other to delete it.|
| Pagination | To change to any page, you can go first to last, previous to next and you can type a specific page. |
> This section is only for Admins
***
### ADMIN ACCESS
| Section | Features |
| - | - |
| Card Authentication | Input with button to send the code from Google Authenticator |
> This section is only for Admins and it will be accesible when you don't have Admin Access
***
# Technologies

* [NodeJS](https://nodejs.org/): Version 14.0.0
* [Docker](https://www.docker.com/): Version 20.10.7
* [Nginx](http://nginx.org/): Version 1.21.5
* [Go](https://go.dev/): Version 1.17
* [Gin-Gonic](https://gin-gonic.com/): Version 1.7.7
* [Vue.js](https://vuejs.org/): Version 3.0.0
* [PHP](https://www.php.net/): Version 8.0.0
* [Laravel](https://laravel.com/): Version 8.65
* [Nest.js](https://nestjs.com/): Version 8.1.5
* [Javascript](https://developer.mozilla.org/es/docs/Web/JavaScript): Version ES6
* [Typescript](https://www.typescriptlang.org/): Version 4.3.5
* [Postgres](https://www.postgresql.org/): Version 14.1
* [PGAdmin](https://www.pgadmin.org/): Version 4