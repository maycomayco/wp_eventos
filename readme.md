# WP - Eventos

Plugin Wordpress para administrar eventos de una manera sencilla.

## Modo de uso

Instalar como un plugin normal y aparecerá en el menu el item para acceder a crear sus eventos.

### Requisitos

* Requiere [WPBakery Page Builder](https://wpbakery.com/) o similar para la vista.
* Requiere [CMB2](https://es.wordpress.org/plugins/cmb2/) para los custom fields.

### Custom query en WPBakery Page Builder

A continuación una *custom query* de ejemplo.

```
post_type=evento&post_status=publish&posts_per_page=3&order=ASC&orderby=meta_value_num&meta_key=_knx-evento_fecha_evento
```

La siguiente query devuelve todos los posts que cumplan con las siguientes condiciones:

*   Que sean del tipo "evento"
*   Que el estado sea "publish"
*   Cómo máximo que devuelva 3 registros
*   Qué éstos esten ordenados de manera ascendete, por un *custom meta field*

Para más información sobre Custom Query, visitar la documentacion oficial de [query_post](https://developer.wordpress.org/reference/functions/query_posts/)

## Autor

* **Mayco Barale** - *Initial work* - [Cero Pixel](http://ceropixel.com.ar)

## Licencia

GNU General Public License family