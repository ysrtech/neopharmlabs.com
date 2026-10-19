@twillBlockTitle('Text and form')
@twillBlockIcon('text')
@twillBlockGroup('landing')

<x-twill::input name="label" label="Section label" :translated="true" />
<x-twill::wysiwyg name="html" label="Text" :translated="true" />
<x-twill::select name="form" label="Form" default="privacy_request" :options="[
    ['value' => 'privacy_request', 'label' => 'Privacy / confidentiality request'],
    ['value' => 'contact', 'label' => 'Contact'],
]" />
