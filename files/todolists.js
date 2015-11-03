(function() {

    /**
     * Vuejs's ViewModel for tasks management
     * @author Andrzej Kupczyk
     */
    window.ToDoList = new Vue({
        el: '#ToDoLists',
        data: {
            tasks: [],
            lang: {},
            newTask: {
                bug_id: 0,
                description: ''
            }
        },
        methods: {
            insertTask: function(e) {
                e.preventDefault();
                if (!this.validateDescription(this.newTask.description)) {
                    return;
                }
                this.$http.post(this.$el.action, {
                    task: this.newTask
                }, function(response) {
                    this.tasks.push(response);
                    this.newTask.description = '';
                });
            },
            updateTask: function(task) {
                return this.$http.put(this.$el.action, {
                    task: task
                });
            },
            deleteTask: function(task, e) {
                e.preventDefault();
                if (!task.finished && !confirm(this.lang.confirmDeletion)) {
                    return;
                }
                this.$http.delete(this.$el.action + '&id=' + task.id, {
                    id: task.id
                }, function() {
                    this.tasks.$remove(task);
                });
            },
            toggleFinished: function(task) {
                task.finished = !!task.finished;
                this.updateTask(task).error(function() {
                    task.finished = !task.finished;
                });
            },
            changeDescription: function(task, e) {
                e.preventDefault();
                var origDesc = task.description,
                    newDesc = prompt(this.lang.enterNewDescription, task.description);
                if (!this.validateDescription(newDesc) || newDesc == origDesc) {
                    return;
                }
                task.description = newDesc;
                this.updateTask(task).error(function() {
                    task.description = origDesc;
                });
            },
            validateDescription: function(description) {
                var length = description ? description.length : 0;
                return length > 0 && length <= 120;
            }
        }
    });

})();