new Vue({
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

      axios.post(this.action, {task: this.newTask}).then((response) => {
        this.tasks.push(...response.data);
        this.newTask.description = this.newTask.descriptionHtml = '';
      });
    },
    updateTask(task) {
      return axios.post(this.action, {task: task}, {
        headers: {'x-http-method-override': 'put'}
      });
    },
    deleteTask(task) {
      if (!task.finished && !confirm(this.lang?.confirmDeletion)) {
        return;
      }

      axios.post(this.action, {task: {id: task.id}}, {
        headers: {'x-http-method-override': 'delete'}
      }).then(() => {
        this.tasks.splice(this.tasks.indexOf(task), 1);
      });
    },
    toggleFinished(task) {
      task.finished = !!task.finished;

      this.updateTask(task).then(
        (response) => task = Object.assign(task, response.data),
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
        (response) => task.descriptionHtml = response.data.descriptionHtml,
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
