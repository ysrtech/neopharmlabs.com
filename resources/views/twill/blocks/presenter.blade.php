@twillBlockTitle('Presenter')
@twillBlockIcon('b-people')
@twillBlockGroup('resources')

<x-twill::input name="name" label="Name" />
<x-twill::input name="role" label="Title" :translated="true" />
<x-twill::wysiwyg name="bio" label="Bio" :translated="true" />
<x-twill::medias name="image" label="Photo" />
