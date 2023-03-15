$('.add_task').on('click', function (e) {
    const id = e.currentTarget.getAttribute('id');
    const dayIndex = id.substring(id.lastIndexOf('_')+1, id.length);
    const nbTasks = $('#tasks_' + dayIndex + ' .task').length;
    let lastTask = $('#tasks_' + dayIndex + ' .task').last().clone()[0];
    lastTask.firstElementChild.textContent = parseInt(lastTask.firstElementChild.textContent) + 1;
    let html = lastTask.outerHTML.replaceAll('_tasks_' + (nbTasks - 1), '_tasks_' + nbTasks)
                    .replaceAll('[tasks]['+ (nbTasks - 1), '[tasks]['+ nbTasks);
    $('#tasks_' + dayIndex).append(html);

    $('#'+dayIndex+'_remove_tasks_'+ (nbTasks)).on('click', removeTask)
    $("div[id^='schedule_workDays_"+dayIndex+"_tasks_"+nbTasks+"'").children('.task_input_time select').on('change', updateTaskTime);
    $('#schedule_workDays_'+dayIndex+'_tasks_'+nbTasks+'_clientName').val('').on('input', autoComplete);
    $("div[id^='schedule_workDays_"+dayIndex+"_tasks_"+nbTasks+"'").children('.time_change_arrows').children(".time_change_up").on('click', increaseValue);
    $("div[id^='schedule_workDays_"+dayIndex+"_tasks_"+nbTasks+"'").children('.time_change_arrows').children('.time_change_down').on('click', decreaseValue);

    $('#schedule_workDays_'+dayIndex+'_tasks_'+(nbTasks - 1)+'_endAt_minute').trigger('change');
    $('#schedule_workDays_'+dayIndex+'_tasks_'+nbTasks+'_beginAt_minute').trigger('change');
});

$('.remove_task').on('click', removeTask);

function removeTask(e) {
    const id = e.currentTarget.getAttribute('id');
    const taskKey = id.substring(id.lastIndexOf('_')+1, id.length);
    const dayIndex = id.substring(0, id.indexOf('_'));
    if ($('#tasks_' + dayIndex + ' .task').length > 1) {
        $('#'+dayIndex+'_remove_tasks_'+taskKey).parent().parent().remove();
    } else {
        $('#schedule_workDays_'+dayIndex+'_tasks_'+taskKey+'_clientName').val('');
        $('#schedule_workDays_'+dayIndex+'_tasks_'+taskKey+'_beginAt_hour').val(5);
        $('#schedule_workDays_'+dayIndex+'_tasks_'+taskKey+'_beginAt_minute').val(0);
        $('#schedule_workDays_'+dayIndex+'_tasks_'+taskKey+'_endAt_hour').val(5);
        $('#schedule_workDays_'+dayIndex+'_tasks_'+taskKey+'_endAt_minute').val(0).trigger('change');
    }
    updateDayDuration(dayIndex);
}

