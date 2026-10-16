@twillBlockTitle('Page hero')
@twillBlockIcon('image')
@twillBlockGroup('landing')

<x-twill::select name="variant" label="Layout" default="simple" :options="[
    ['value' => 'home', 'label' => 'Home (full-width image + video)'],
    ['value' => 'about', 'label' => 'About (title + two-column text)'],
    ['value' => 'careers', 'label' => 'Careers (centered with button)'],
    ['value' => 'contact', 'label' => 'Contact (with contact form and map)'],
    ['value' => 'simple', 'label' => 'Simple title'],
]" />
<x-twill::wysiwyg name="title_html" label="Title" note="Bold text is highlighted" :translated="true" :toolbar-options="['bold']" />
<x-twill::wysiwyg name="text_html" label="Text" :translated="true" />
<x-twill::input name="button_label" label="Button label" :translated="true" />
<x-twill::input name="button_url" label="Button link" :translated="true" />
<x-twill::input name="video_url" label="Video embed URL (home layout)" />
<x-twill::input name="video_label" label="Video link label" :translated="true" />
<x-twill::medias name="image" label="Background / side image" />
