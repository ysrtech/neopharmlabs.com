@twillBlockTitle('Quote')
@twillBlockIcon('quote')
@twillBlockGroup('content')

<x-twill::input name="quote" label="Quote" type="textarea" :rows="4" :translated="true" />
<x-twill::input name="author" label="Author" />
<x-twill::input name="role" label="Author title" :translated="true" />
