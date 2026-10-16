@twillBlockTitle('Call to action box')
@twillBlockIcon('text')
@twillBlockGroup('content')

<x-twill::wysiwyg name="html" label="Text" :translated="true" :edit-source="true" />
<x-twill::select name="style" label="Background" default="primary" :options="[
    ['value' => 'primary', 'label' => 'Blue'],
    ['value' => 'dark', 'label' => 'Dark'],
    ['value' => 'light', 'label' => 'Light'],
]" />
<x-twill::input name="button_label" label="Button label" :translated="true" />
<x-twill::input name="button_url" label="Button link" :translated="true" note="Leave empty to link to the Contact page" />
