# Site

http://ruslan-website.com/joomla/trip_blog/

# Install

`git clone -b 3.9.12 --single-branch --depth 1 https://github.com/joomla/joomla-cms.git trip_blog`

# Tutorials

* https://www.youtube.com/playlist?list=PLtaXuX0nEZk_4XIvoPA7O0xT_sYRKnTos
* https://docs.joomla.org/J2.5:Developing_a_MVC_Component/Introduction
* https://docs.joomla.org/J3.x:Developing_an_MVC_Component/Introduction
 - https://github.com/Stevec4/Joomla-HelloWorld

* https://github.com/joomlatools/joomlatools-todo
* https://github.com/joomla-extensions/boilerplate

* https://api.joomla.org/cms-3/index.html
* https://docs.joomla.org/Main_Page

## Other Tutorials

- Frontend Joomla toolbar https://docs.joomla.org/J3.x:Using_the_JToolbar_class_in_the_frontend
- Chosen.js https://harvesthq.github.io/chosen/
- FooTable.js https://fooplugins.github.io/FooTable/
- JS Validation https://docs.joomla.org/Client-side_form_validation

## Language

- https://docs.joomla.org/J3.x:Setup_a_Multilingual_Site/Adding_Multilingual_Content
- https://docs.joomla.org/J3.x:Developing_an_MVC_Component/Using_the_language_filter_facility
- https://docs.joomla.org/J3.x:Developing_an_MVC_Component/Adding_Associations

## Appearances

* Module https://docs.joomla.org/J3.x:Creating_a_simple_module/Developing_a_Basic_Module
* Wright template 
 - https://wright.joomlashack.com/
 - https://www.youtube.com/playlist?list=PLtaXuX0nEZk8NKMoEpaAQdb5MbRUHmMrF
* Customize template, menus' and modules' positions 
 - https://www.balbooa.com/knowledgebase/32-documentation-faq-joomla/177-how-to-add-module-position-in-joomla
 - https://www.joomlart.com/forums/topic/how-to-create-a-new-module-position-updated/
 - In Wright, it's `js_wright/template.php`'s `<w:module>` or `<w:xxx>` tags.

## Google map

- https://docs.joomla.org/J3.x:Developing_an_MVC_Component/Adding_a_Map
- https://developers.google.com/maps/documentation/javascript/tutorial
- https://developers.google.com/maps/documentation/javascript/events

# Notes

## Which classes to extend

| Purpose  | Controller  | Model  |
|---|---|---|
| Base  | JControllerLegacy  | JModelLegacy  |
| Plural  | JControllerAdmin  | JModelList  |
| Single  | JControllerForm  | JModelAdmin  |

### Controllers

| J1.5  | J2.5  | J3.0+  |
|---|---|---|
| JController  | JControllerLegacy  | JControllerLegacy  |
|   | - JControllerAdmin  | - JControllerAdmin  |
|   | - JControllerForm  | - JControllerForm  |
|   |   | JControllerBase (new MVC)  |

The master controller of every component `controller.php` should extend `JControllerLegacy`.

https://docs.joomla.org/JController_and_its_subclass_usage_overview

### Models

`JModel` is the Interface. Don't use that directly.

`JModelBase` is the base class for a model. It con tains some very basic functions. 
You can use that, but be warned, you need to write a lot of own code if you use this own.

* JModelLegacy (Abstract)
 * JModelForm (Abstract)
  * JModelAdmin (Abstract)
 * JModelItem (Abstract)
 * JModelList

`JModelLegacy`, `JModelForm`, `JModelList`, `JModelItem` and `JModelAdmin` are the ones which are used the most,
because they contain most of the functions we need in the CMS. 

The names indicate where you can use that: Form for
forms (eq: supporting JForm stuff), List for listings (eq: supporting pagination), ...

https://stackoverflow.com/questions/21704490/what-are-the-differences-between-joomla-model-types

### Class Map

```php
JLoader::registerAlias('JModelAdmin', '\\Joomla\\CMS\\MVC\\Model\\AdminModel', '5.0');
JLoader::registerAlias('JModelForm', '\\Joomla\\CMS\\MVC\\Model\\FormModel', '5.0');
JLoader::registerAlias('JModelItem', '\\Joomla\\CMS\\MVC\\Model\\ItemModel', '5.0');
JLoader::registerAlias('JModelList', '\\Joomla\\CMS\\MVC\\Model\\ListModel', '5.0');
JLoader::registerAlias('JModelLegacy', '\\Joomla\\CMS\\MVC\\Model\\BaseDatabaseModel', '5.0');
JLoader::registerAlias('JViewCategories', '\\Joomla\\CMS\\MVC\\View\\CategoriesView', '5.0');
JLoader::registerAlias('JViewCategory', '\\Joomla\\CMS\\MVC\\View\\CategoryView', '5.0');
JLoader::registerAlias('JViewCategoryfeed', '\\Joomla\\CMS\\MVC\\View\\CategoryFeedView', '5.0');
JLoader::registerAlias('JViewLegacy', '\\Joomla\\CMS\\MVC\\View\\HtmlView', '5.0');
JLoader::registerAlias('JControllerAdmin', '\\Joomla\\CMS\\MVC\\Controller\\AdminController', '5.0');
JLoader::registerAlias('JControllerLegacy', '\\Joomla\\CMS\\MVC\\Controller\\BaseController', '5.0');
JLoader::registerAlias('JControllerForm', '\\Joomla\\CMS\\MVC\\Controller\\FormController', '5.0');
```

https://github.com/joomla/joomla-cms/blob/staging/libraries/classmap.php

## Include Paths

| For what  | How  | Where  | Notes  |
|---|---|---|---|
| Joomla View  | `jimport('joomla.application.component.view')`  |   |   |
| Joomla Controller  | `jimport('joomla.application.component.controller')`  |   |   |
| Joomla Model  | `jimport('joomla.application.component.model');`  |   |   |
| Extension Model  | `JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_xxx/models', 'YyyModel');`  | Before `JModelLegacy::getInstance ('Zzz', 'YyyModel', array('ignore_request' => true));`  | https://api.joomla.org/cms-3/classes/Joomla.CMS.MVC.Model.BaseDatabaseModel.html#method_addIncludePath  |
| Extension Table  | `Table::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_xxx/tables')`  | Before `Table::getInstance('Yyy', 'XxxTable')`  |  |
| Html helpers  | `HTMLHelper::addIncludePath(JPATH_LIBRARIES . '/html')`  | eg in `library.php`  | https://api.joomla.org/cms-3/classes/Joomla.CMS.HTML.HTMLHelper.html#method_addIncludePath  |
| Extension helpers  | `jimport('component.helper.helperfile')`  |   |   |
| Form  | `FormHelper::addFormPath(JPATH_SITE . '/components/com_xxx/models/forms')`  |   |   |
| Field (php) | `FormHelper::addFieldPath(JPATH_SITE . '/components/com_xxx/models/fields')`  |   |   |
| Field (xml) | `<fieldset addfieldpath="/libraries/{component_name}/form/fields">`  | In `models\forms\xxx.xml`  |   |
| Base field | `FormHelper::loadFieldClass('list')`  | When defining custom field. Before `class JFormFieldCustomField extends JFormFieldList`  |   |
| Rule  | `FormHelper::addRulePath(JPATH_SITE . '/components/com_xxx/models/rule')`  |   |   |
| Layout  | `LayoutHelper::render('path.file', $data, JPATH_SITE . '/components/com_xxx/layouts', $options)`  | 3rd parameter of `LayoutHelper::render`  | https://docs.joomla.org/J3.x:Sharing_layouts_across_views_or_extensions_with_JLayout , https://docs.joomla.org/J3.x:JLayout_Improvements_for_Joomla!  |
| Language  | `Factory::getLanguage()->load('com_xxx', JPATH_SITE . '/components/com_xxx', 'en-GB')`  |   |   |
| Plugin  | `PluginHelper::importPlugin( 'group' )`  | Before `JEventDispatcher::getInstance()->trigger( 'onXxx', array( &$param1, &$param2 ) )`  | https://docs.joomla.org/Supporting_plugins_in_your_component  |

### Other notes

- `jimport()` is the same as `JLoader::import` https://github.com/joomla/joomla-cms/blob/staging/libraries/loader.php
- https://joomla.stackexchange.com/questions/7170/jloaderimport-vs-jloaderregister/7601

## Template overrides

| Extension type  | Original path  | Override path  |
|---|---|---|
| Component view  | `{domain}/components/com_xxx/views/feature/tmpl/default.php`  | `{domain}/templates/{template}/html/com_xxx/feature/default.php`  |
| Component layout  | `{domain}/components/com_xxx/layouts/path/file.php`  | `{domain}/templates/{template}/html/layouts/com_xxx/path/file.php`  |
| Module layout  | `{domain}/modules/mod_xxx/tmpl/default.php`  | `{domain}/templates/{template}/html/mod_xxx/default.php`  |
| Plugin layout  | ``  | ``  |

## Loosen FK constraint

Sometimes (eg in install or update scripts), you need to loosen FK constraint like below:
```
$db->setQuery('SET FOREIGN_KEY_CHECKS=0;')->execute();
$db->setQuery("ALTER TABLE table_name DROP FOREIGN KEY fk_name;")->execute();
```

## Retrieving values

| From  | Code  | Notes  |
|---|---|---|
| `configuration.php`  | `Factory::getConfig()->get('live_site')`  |   |
| Site Directory Root  | `JPATH_SITE`  | No final '/'  |
| URL Root  | `Uri::root()`  | Equivalent of (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']  |
| Language tag (eg `en-GB`)  | `LanguageMultilang::isEnabled(){Factory::getLanguage()->getTag();}`  |   |
| Global config  | `Factory::getApplication()->getParams('com_xxx')->get('key', '')`  |   |
| Menu item settings (method 1)  | `Factory::getApplication()->getMenu()->getActive()->params->get('key', '')`  |   |
| Menu item settings (method 2)  | `Factory::getApplication()->getMenu()->getItem($itemId)->params->get('key', '')`  | Where `$itemId = Factory::getApplication()->input->get('Itemid')`  |
| GET (string) | `Factory::getApplication()->input->get('xxx', '')`  | https://docs.joomla.org/Retrieving_request_data_using_JInput  |
| GET (int)  | `Factory::getApplication()->input->getInt('yyy', 0)`  |   |
| GET (all as array)  | `Factory::getApplication()->input->getArray()`  |   |
| GET (form inputs as array)  | `Factory::getApplication()->input->get('jform', array(), 'array')`  |   |
| POST (string)  | `Factory::getApplication()->input->post->get('xxx', '')`  |   |
| POST (int)  | `Factory::getApplication()->input->post->getInt('yyy', 0)`  |   |
| POST (all as array)  | `Factory::getApplication()->input->post->getArray()`  |   |
| POST (form inputs as array)  | `Factory::getApplication()->input->post->get('jform', array(), 'array')`  |   |
| GET and POST (method 1)  | `Factory::getApplication()->input->{$method}->get('xxx', '')`  | Where `$method` is `Factory::getApplication()->input->getMethod()`  |
| GET and POST (method 2)  | `JRequest::getVar('address', 'default value goes here', 'post','variable type')`  | https://docs.joomla.org/J1.5:Retrieving_and_Filtering_GET_and_POST_requests_with_JRequest::getVar , https://api.joomla.org/cms-3/classes/JRequest.html  |
| Table instance  | Table::getInstance('Yyy', 'XxxTable')  |   |
| Model instance  | `$model = AdminModel::getInstance('Yyy', 'XxxModel')`  |   |
| Model state  | `$model->getState('key', '', 'string')`  |   |
| Session  | `Factory::getSession()->get('xx.xxx', null, 'namespace')`  | https://api.joomla.org/cms-3/classes/Joomla.Session.Session.html  |
| Session (all as array)  | `Factory::getSession()->getData()->toArray()`  |   |
| Session (set a string)  | `Factory::getSession()->set('xxx', 'yyy')`  |   |
| Session (set an array)  | `Factory::getSession()->set('xxx', array('key' => 'value'))`  |   |
| Session (clear)  | `Factory::getSession()->clear('xxx')`  |   |
| User  | `Factory::getUser()`  | To check super: `Factory::getUser()->authorise('core.admin')`  |
| User state  | `Factory::getApplication()->getUserState('xx.xxx', '')`  |   |
| Input then User state  | `Factory::getApplication()->getUserStateFromRequest('model.state.key', 'request_var_name', '', 'string')`  | https://api.joomla.org/cms-3/classes/JApplication.html#method_getUserStateFromRequest  |
| Plugin param (inside plugin)  | `$pluginParams = $this->params`  |   |
| Plugin param (outside plugin)  | `$pluginParams = PluginHelper::getPlugin('type', 'name')->params`  | https://stackoverflow.com/questions/13152440/how-to-get-the-params-value-of-plugin-in-the-component-area-in-joomla2-5  |
| Registry  | `$params = new Registry($pluginParams);$params->get('param_name', 'default_value');`  | https://api.joomla.org/cms-3/classes/Joomla.Registry.Registry.html . `JParameter` before Joomla 1.6 |

## Rendering layouts

`LayoutHelper::render('xxx.yyy', array('key' => 'data'))` will render `layouts/xxx/yyy.php`.

Inside the `layouts/xxx/yyy.php` file, `array('key' => 'data')` becomes `$displayData`.

Doing `extract($displayData)` will extract `$displayData` into PHP variables, eg `$key`.

---

## To Do

- Maps (Ref: https://jsfiddle.net/atabegaslan/3pzzpt7o/)
- Google ReCaptcha for user registration
- Social media logins
- Web Servives (Ref: https://github.com/redCOMPONENT-COM/redCORE/tree/develop/extensions/libraries/redcore/api)
- Include and use SEF and Logman plugins
- Use chosen.js and footable.js
