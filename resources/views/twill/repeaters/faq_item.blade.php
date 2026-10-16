@twillRepeaterTitle('Question')
@twillRepeaterTrigger('Add question')
@twillRepeaterGroup('app')

<x-twill::input name="question" label="Question" :translated="true" />
<x-twill::wysiwyg name="answer" label="Answer" :translated="true" :edit-source="true" />
