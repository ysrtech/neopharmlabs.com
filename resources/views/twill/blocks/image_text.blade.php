@twillBlockTitle('Image and text section')
@twillBlockIcon('image-text')
@twillBlockGroup('landing')

<x-twill::input name="label" label="Section label" :translated="true" />
<x-twill::wysiwyg name="html" label="Text" :translated="true" :edit-source="true" />
<x-twill::select name="style" label="Background" default="bg-light" :options="[
    ['value' => 'bg-primary', 'label' => 'Blue'],
    ['value' => 'bg-light', 'label' => 'Light'],
    ['value' => 'bg-midnight', 'label' => 'Midnight'],
    ['value' => 'no-box', 'label' => 'None'],
]" />
<x-twill::select name="layout" label="Image layout" default="fullHeight" :options="[
    ['value' => 'fullHeight', 'label' => 'Full height'],
    ['value' => 'centered', 'label' => 'Centered'],
]" />
<x-twill::checkbox name="reverse" label="Image on the left" />
<x-twill::input name="button_label" label="Button label" :translated="true" />
<x-twill::input name="button_url" label="Button link" :translated="true" />
<x-twill::select name="button_style" label="Button style" default="primary" :options="[
    ['value' => 'primary', 'label' => 'Blue'],
    ['value' => 'white', 'label' => 'White'],
]" />
<x-twill::medias name="image" label="Image" />
