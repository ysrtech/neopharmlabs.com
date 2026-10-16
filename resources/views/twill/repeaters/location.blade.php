@twillRepeaterTitle('Location')
@twillRepeaterTrigger('Add location')
@twillRepeaterGroup('app')

<x-twill::input name="descriptor" label="Small heading" type="textarea" :translated="true" />
<x-twill::wysiwyg name="title_html" label="Title" :translated="true" :toolbar-options="['bold']" />
<x-twill::input name="phone" label="Phone" />
<x-twill::input name="email" label="Email" />
<x-twill::input name="address" label="Address" type="textarea" :translated="true" />
<x-twill::input name="map_url" label="Map link" :translated="true" />
<x-twill::medias name="image" label="Image" />
