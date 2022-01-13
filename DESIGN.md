
# Resumen proyecto DAW asignatura interfaces web.
Este documento explicará el trabajo realizado en el proyecto DAW tiene el objetivo de poder qualificar la interfaz realizada para la asignatura interfaces web por el profesor Javi.


## Home
El home contiene de un slider donde se repiten imágenes, toda su funcionalidad ha sido programado desde 0 por Iván ya que vuestic desgraciadamente no contiene ningún slider de imagenes.

![image](https://github.com/Land-of-Devs/Web-Panel-Vue3-Go-Laravel-Nest/blob/master/design1.png?raw=true)

Consideramos evaluable el contenido scss empezando desde la siguiente [línea](https://github.com/Land-of-Devs/Web-Panel-Vue3-Go-Laravel-Nest/blob/master/frontend/src/components/global/ImageCarousel.vue#L89)


## Signin Modal
El modal signin ha sido realizado utilizando el componente [va-modal](https://vuestic.dev/en/ui-elements/modal) 

![image](https://github.com/Land-of-Devs/Web-Panel-Vue3-Go-Laravel-Nest/blob/master/design2.png?raw=true)

También se utilizan los componentes: [va-input](https://vuestic.dev/en/ui-elements/input), [va-button](https://vuestic.dev/en/ui-elements/button), [va-form](https://vuestic.dev/en/ui-elements/form)

Fichero: [SignInModal.vue](https://github.com/Land-of-Devs/Web-Panel-Vue3-Go-Laravel-Nest/blob/master/frontend/src/components/login/SignInModal.vue)


## Signup Modal
El modal signup no tiene mucha diferencia al signin modal

![image](https://github.com/Land-of-Devs/Web-Panel-Vue3-Go-Laravel-Nest/blob/master/design3.png?raw=true)

Fichero: [SignUpModal.vue](https://github.com/Land-of-Devs/Web-Panel-Vue3-Go-Laravel-Nest/blob/master/frontend/src/components/login/SignUpModal.vue)


## Shop 
El shop está compuesto por una lista de componentes [va-card](https://vuestic.dev/en/ui-elements/card) y la paginación con [va-pagination](https://vuestic.dev/en/ui-elements/pagination)
Los componentes va-card son responsives con un sistema similar a bootstrap usando las clases: flex, xs, sm, md, lg

![image](https://github.com/Land-of-Devs/Web-Panel-Vue3-Go-Laravel-Nest/blob/master/design4.png?raw=true)
Consideramos evaluable el contenido html y scss del siguiente archivo: [ProductElement.vue](https://github.com/Land-of-Devs/Web-Panel-Vue3-Go-Laravel-Nest/blob/master/frontend/src/components/shop/ProductElement.vue)


## Navbar
El navbar se muestra en todas las partes de la aplicación excepto en el panel, usando el siguiente componente [va-navbar](https://vuestic.dev/en/ui-elements/navbar)

Fichero [NavBar.vue](https://github.com/Land-of-Devs/Web-Panel-Vue3-Go-Laravel-Nest/blob/master/frontend/src/components/NavBar.vue)


## Modal Ticket
La aplicación proporciona un modal para crear distintos tipos de tickets 
![image](https://github.com/Land-of-Devs/Web-Panel-Vue3-Go-Laravel-Nest/blob/master/design5.png?raw=true)


