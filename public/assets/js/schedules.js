var $collectionHolders;

jQuery(document).ready(function() {
    $collectionHolders = $('div.tasks');

    resetAddRemoveListeners();
});

function resetAddRemoveListeners() {
    $('.add_task').off();
    $('.remove_task').off();
    $('.add_task').on('click', function (e) {
        addTaskForm($collectionHolders, e)
    })

    $('.remove_task').on('click', removeTask);
}

function resetCounterListeners(dayIndex, i) {
    $element = $("div[id^='schedule_workDays_"+dayIndex+"_tasks_"+i+"'");
    $element.children('.task_input_time select').off();
    $element.children('.time_change_arrows').children(".time_change_up").off();
    $element.children('.time_change_arrows').children('.time_change_down').off();
    $element.children('.task_input_time select').off();
    const $clientName = $element.children('.client').children('input');
    $clientName.off();
    
    $element.children('.task_input_time select').on('change', updateTaskTime);
    $element.children('.time_change_arrows').children(".time_change_up").on('click', increaseValue);
    $element.children('.time_change_arrows').children('.time_change_down').on('click', decreaseValue);
    $element.children('.task_input_time select').on('change', updateTaskTime)
    $clientName.on('input', autoComplete);
    $clientName.on('change', updateClientName)
    $("select[id$='_endAt_hour'").trigger('change')
}

function addTaskForm ($collectionHolders, e) {
    const prototype = $collectionHolders.data('prototype');
    
    const id = e.currentTarget.getAttribute('id');
    const dayIndex = id.substring(0,id.indexOf('_'));
    const index = parseInt(id.substring(id.lastIndexOf('_')+1, id.length))+1;
    
    let newForm = prototype;
    newForm = newForm.replace(/__task_prot__/g, index);
    newForm = newForm.replace(/0_days/g, dayIndex+'_days');
    newForm = newForm.replace(/workDays\]\[0/g, 'workDays]['+dayIndex);
    newForm = newForm.replace(/workDays_0/g, 'workDays_'+dayIndex);
    $(newForm).insertAfter('#schedule_workDays_'+dayIndex+'_tasks_'+(index-1));
    
    $('#tasks_' + dayIndex).children('.task').each(function (i, element) {
        const $element = $(element);
        const $clientInput = $element.children().children('.client_name').first()
        $clientInput.attr('value', $clientInput.val());
        $element.children().children().children('select').each(function(i, e){
            const value = $(e).val();
            $(e).children('option[value='+value+']').attr('selected', 'selected');
            $(e).children('option[value!='+value+']').removeAttr('selected');
        })
        $element.html($element.html().replaceAll(/_tasks_\d+/g, '_tasks_'+i));
        $element.html($element.html().replaceAll(/\[tasks\]\[\d+\]/g, '[tasks]['+i+']'));
        $element.attr('id', 'schedule_workDays_'+dayIndex+'_tasks_'+i);

        resetCounterListeners(dayIndex, i);
    })

    addChangeArrows(dayIndex, index);
        
    resetCounterListeners(dayIndex, index);

    resetAddRemoveListeners();
};

function removeTask(e) {
    const id = e.currentTarget.getAttribute('id');
    const taskKey = id.substring(id.lastIndexOf('_')+1, id.length);
    const dayIndex = id.substring(0, id.indexOf('_'));
    if ($('#tasks_' + dayIndex + ' .task').length > 1) {

        $('#tasks_' + dayIndex).children('.task').each(function (i, element) {
            $element = $(element);
            const $clientInput = $element.children().children('.client_name').first()
            $clientInput.attr('value', $clientInput.val());
            $element.children().children().children('select').each(function(i, e){
                const value = $(e).val();
                $(e).children('option[value='+value+']').attr('selected', 'selected');
                $(e).children('option[value!='+value+']').removeAttr('selected');
            })
        })

        $('#'+dayIndex+'_days_remove_tasks_'+taskKey).parent().parent().remove();
        $('#tasks_' + dayIndex).children('.task').each(function (i, element) {
            const $element = $(element);
            $element.html($element.html().replaceAll(/_tasks_\d+/g, '_tasks_'+i));
            $element.html($element.html().replaceAll(/\[tasks\]\[\d+\]/g, '[tasks]['+i+']'));
            $element.attr('id', 'schedule_workDays_'+dayIndex+'_tasks_'+i);

            resetCounterListeners(dayIndex, i);
        })
    } else {
        $('#schedule_workDays_'+dayIndex+'_tasks_'+taskKey+'_clientName').val('');
        $('#schedule_workDays_'+dayIndex+'_tasks_'+taskKey+'_beginAt_hour').val(5);
        $('#schedule_workDays_'+dayIndex+'_tasks_'+taskKey+'_beginAt_minute').val(0);
        $('#schedule_workDays_'+dayIndex+'_tasks_'+taskKey+'_endAt_hour').val(5);
        $('#schedule_workDays_'+dayIndex+'_tasks_'+taskKey+'_endAt_minute').val(0).trigger('change');
    }
    updateDayDuration(dayIndex);
    resetAddRemoveListeners();
}

