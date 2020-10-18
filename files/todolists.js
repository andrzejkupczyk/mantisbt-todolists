(() => {

  window.ToDoList = new Vue({
    el: '#ToDoLists',
    data: {
      lang: {},
      newTask: {
        bug_id: 0,
        description: '',
      },
      readOnly: false,
      tasks: [],
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

        this.$http.post(this.action, { task: this.newTask }, (response) => {
          this.tasks.push(...response);
          this.newTask.description = '';
        });
      },
      updateTask(task) {
        return this.$http.put(this.action, { task: task });
      },
      deleteTask(task) {
        if (!task.finished && !confirm(this.lang.confirmDeletion)) {
          return;
        }

        this.$http.delete(
          this.action + '&id=' + task.id, { id: task.id },
          () => this.tasks.$remove(task),
        );
      },
      toggleFinished(task) {
        task.finished = !!task.finished;

        this.updateTask(task).error(() => {
          task.finished = !task.finished;
        });
      },
      changeDescription(task) {
        const origDesc = task.description;
        const newDesc = prompt(this.lang.enterNewDescription, task.description);

        if (!this.validateDescription(newDesc) || newDesc === origDesc) {
          return;
        }

        task.description = newDesc;

        this.updateTask(task).error(() => {
          task.description = origDesc;
        });
      },
      validateDescription(description) {
        return (description ? description.length : 0) > 0;
      },
    },
  });

})();
