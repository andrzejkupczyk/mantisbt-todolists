const form = document.querySelector('.ToDoLists form')
const submitBtn = form.querySelector('button[type="submit"]')
const textarea = form.querySelector('textarea');

document.body.addEventListener('EVENT_TODOLISTS_REQUEST_HANDLED', function () {
  form.reset()
  textarea.style.height = '0'
  form.querySelector('button').blur()
})

textarea.addEventListener('keypress', function (event) {
  if (event.key !== 'Enter' || event.shiftKey) return
  event.preventDefault()
  submitBtn.dispatchEvent(new MouseEvent('click'))
})
