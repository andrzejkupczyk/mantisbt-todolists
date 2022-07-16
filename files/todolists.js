const events = {
  requestHandled: 'EVENT_TODOLISTS_REQUEST_HANDLED'
}

const form = document.querySelector('.ToDoLists form')

document.body.addEventListener(events.requestHandled, function () {
  form.reset()
  form.querySelector('button').blur()
})
