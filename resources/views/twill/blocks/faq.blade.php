@twillBlockTitle('FAQ accordion')
@twillBlockIcon('b-accordion')
@twillBlockGroup('content')

<x-twill::input name="title" label="Title" :translated="true" />
<x-twill::repeater type="faq_item" />
