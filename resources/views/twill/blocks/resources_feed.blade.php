@twillBlockTitle('Latest posts and resources')
@twillBlockIcon('flex-grid')
@twillBlockGroup('landing')

<x-twill::wysiwyg name="html" label="Heading" :translated="true" />
<x-twill::input name="text" label="Text" type="textarea" :translated="true" />
<x-twill::input name="button_label" label="Button label" :translated="true" />
<x-twill::input name="button_url" label="Button link" :translated="true" />
<x-twill::input name="post_count" label="Number of blog posts" type="number" default="6" />
