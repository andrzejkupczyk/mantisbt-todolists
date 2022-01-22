(() => {

  Vue.http.options.emulateHTTP = true;

  window.ToDoList = new Vue({
    el: '#ToDoLists',
    props: ['currentTasks', 'isReadonly', 'translations'],
    data: {
      newTask: {
        bug_id: 0,
        description: '',
        descriptionHtml: '',
      },
      tasks: [],
      lang: undefined
    },
    computed: {
      action() {
        return this.$el.getElementsByTagName('FORM')[0].action;
      },
      finishedTasks() {
        return this.tasks.filter((task) => task.finished);
      },
      counter() {
        const tasksLeft = this.tasks.length - this.finishedTasks.length;

        return [tasksLeft, this.tasks.length].join('/');
      },
    },
    methods: {
      insertTask() {
        if (!this.validateDescription(this.newTask.description)) {
          return;
        }

        this.$http.post(this.action, {task: this.newTask}).then((response) => {
          this.tasks.push(...response.body);
          this.newTask.description = this.newTask.descriptionHtml = '';
        });
      },
      updateTask(task) {
        return this.$http.put(this.action, {task: task});
      },
      deleteTask(task) {
        if (!task.finished && !confirm(this.lang?.confirmDeletion)) {
          return;
        }

        this.$http.delete(this.action, {body: {task: {id: task.id}}})
          .then(() => this.tasks.$remove(task));
      },
      toggleFinished(task) {
        task.finished = !!task.finished;

        this.updateTask(task).then(
          (response) => task = Object.assign(task, response.body),
          () => task.finished = !task.finished
        );
      },
      changeDescription(task) {
        const origDesc = task.description;
        const newDesc = prompt(this.lang?.enterNewDescription, task.description);

        if (!this.validateDescription(newDesc) || newDesc === origDesc) {
          return;
        }

        task.description = newDesc;

        this.updateTask(task).then(
          (response) => task.descriptionHtml = response.body.descriptionHtml,
          () => task.description = origDesc
        );
      },
      validateDescription(description) {
        return (description ? description.length : 0) > 0;
      },
    },
    created() {
      this.tasks = JSON.parse(this?.currentTasks);
      this.lang = JSON.parse(this?.translations);
    },
  });

})();
