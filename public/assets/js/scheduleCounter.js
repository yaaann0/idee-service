$('.task_input_time select').on('change', updateTaskTime)
$("select[id$='_beginAt_hour'").trigger('change')
$('.client_name').on('change', updateClientName)

addChangeArrows();

$('.time_change_up').on('click', increaseValue);
$('.time_change_down').on('click', decreaseValue);

$('#schedules_form').on('submit', function (e) {
    const durations = $('.durations');
    const $comment = $('#schedule_comment');

    const total = parseFloat($('#duration_week').html());

    if (!total && $comment.length && !$comment.val()) {      
        $('#jquery_form_errors').addClass('alert alert-danger').html('Motif à préciser pour nombre d\'heures à 0')
        e.preventDefault();
    }

    for (let index = 0; index < durations.length; index++) {
        const duration = parseFloat(durations[index].value);
        const id = durations[index].id;
        const taskKey = id.substring(id.lastIndexOf('_')+1, id.length);
        const dayIndex = id.substring(0, id.indexOf('_'));
        
        if ($('#schedule_workDays_'+dayIndex+'_tasks_'+taskKey+'_clientName').val() && duration <= 0) {
            const inputs = $('#schedule_workDays_' + dayIndex + '_tasks_' + taskKey+'_endAt').children('select');
            inputs[0].setCustomValidity('La durée ne peut être de 0 h');
            inputs[1].setCustomValidity('La durée ne peut être de 0 h');
            $('#jquery_form_errors').addClass('alert alert-danger').html('Certaines interventions ont une durée de 0 h')

            e.preventDefault();

            inputs.on('change', function (e) {
                inputs[0].setCustomValidity('');
                inputs[1].setCustomValidity('');
                $('#jquery_form_errors').removeClass('alert alert-danger').html('')
            })
        }
    }
})

function addChangeArrows($day, $index) {
    $changeArrows = '<div class="d-flex flex-column justify-content-between time_change_arrows"><button type="button" class="btn btn-outline-secondary p-0 rounded-lg d-flex align-items-center justify-content-center time_change_up"><i class="fas fa-sort-up"></i></button><button type="button" class="btn btn-outline-secondary p-0 rounded-lg d-flex align-items-center justify-content-center time_change_down"><i class="fas fa-sort-down"></i></button></div>'

    if (!$day || !$index) {
        $('.time_change_arrows').remove()
        $('.task_input_time select').after($changeArrows); 
    } else {
        $("div[id^='schedule_workDays_"+$day+"_tasks_"+$index+"'").children('select').after($changeArrows);
        $("div[id^='schedule_workDays_"+$day+"_tasks_"+$index+"'").children('.time_change_up').on('change', increaseValue);
        $("div[id^='schedule_workDays_"+$day+"_tasks_"+$index+"'").children('.time_change_up').on('change', decreaseValue);
    }
}

function updateTaskTime(e) {
    const id = e.currentTarget.getAttribute('id');
    const day = id.match(/workDays_\d{1}/)[0];
    const task = id.match(/tasks_\d+/)[0];
    const dayIndex = day.substring(day.indexOf('_')+1, day.length);
    const taskKey = task.substring(task.indexOf('_')+1, task.length)

    const beginHours = parseInt($('#schedule_workDays_' + dayIndex + '_tasks_' + taskKey + '_beginAt_hour option:selected')[0].value);
    const beginMinutes = parseInt($('#schedule_workDays_' + dayIndex + '_tasks_' + taskKey + '_beginAt_minute option:selected')[0].value) / 60;
    const endHours = parseInt($('#schedule_workDays_' + dayIndex + '_tasks_' + taskKey + '_endAt_hour option:selected')[0].value);
    const endMinutes = parseInt($('#schedule_workDays_' + dayIndex + '_tasks_' + taskKey + '_endAt_minute option:selected')[0].value) / 60;

    const begin = beginHours + beginMinutes;
    const end = endHours + endMinutes;
    const duration = end - begin;
    $('#' + dayIndex + '_days_duration_tasks_' + taskKey).attr('value', duration+' h').trigger('change');
    updateDayDuration(dayIndex);
    checkDuration(dayIndex, taskKey, begin, end)
    checkOverlapTasks(dayIndex, parseInt(taskKey), begin, end);
    checkEnd(dayIndex, parseInt(taskKey), end)
}

function checkOverlapTasks(dayIndex, taskKey, begin, end) {
    $current = $('#schedule_workDays_' + dayIndex + '_tasks_' + taskKey);
    const currentTaskBegin = $current.children('div').children('.task_begin').children('select');
    const currentTaskEnd = $current.children('div').children('.task_end').children('select');

    const prevTask = $current.prev().children('div').children('.task_end').children('select');

    const nextTask = $current.next().children('div').children('.task_begin').children('select');

    if (prevTask.length) {
        const prevEnd = parseInt(prevTask[0].options[prevTask[0].selectedIndex].value) + (parseInt(prevTask[1].options[prevTask[1].selectedIndex].value) / 60);

        if (begin < prevEnd) {
            $(prevTask[0]).val(currentTaskBegin[0].options[currentTaskBegin[0].selectedIndex].value);
            $(prevTask[1]).val(currentTaskBegin[1].options[currentTaskBegin[1].selectedIndex].value).trigger('change');
        }
    }

    if (nextTask.length) {
        const nextBegin = parseInt(nextTask[0].options[nextTask[0].selectedIndex].value) + (parseInt(nextTask[1].options[nextTask[1].selectedIndex].value) / 60);

        if (end > nextBegin) {
            $(nextTask[0]).val(currentTaskEnd[0].options[currentTaskEnd[0].selectedIndex].value);
            $(nextTask[1]).val(currentTaskEnd[1].options[currentTaskEnd[1].selectedIndex].value).trigger('change');
        }
    }
}

function checkEnd(dayIndex, taskKey, end) {

    let message = '';

    if (end > 21) {
        message = 'Maximum 21 h'
    }

    const inputs = $('#schedule_workDays_' + dayIndex + '_tasks_' + taskKey + '_endAt').children('select');

    inputs[0].setCustomValidity(message);
    inputs[1].setCustomValidity(message);
}

function checkDuration(dayIndex, taskKey, begin, end) {
    if (begin > end) {
        const taskBegin = $('#schedule_workDays_' + dayIndex + '_tasks_' + taskKey + '_beginAt').children('select');
        $('#schedule_workDays_' + dayIndex + '_tasks_' + taskKey + '_endAt_hour').val(taskBegin[0].options[taskBegin[0].selectedIndex].value);
        $('#schedule_workDays_' + dayIndex + '_tasks_' + taskKey + '_endAt_minute').val(taskBegin[1].options[taskBegin[1].selectedIndex].value).trigger('change');
    }
}

function updateClientName(e) {
    const id = e.currentTarget.getAttribute('id');
    const day = id.match(/workDays_\d{1}/)[0];
    const task = id.match(/tasks_\d+/)[0];
    const dayIndex = day.substring(day.indexOf('_')+1, day.length);
    const taskKey = task.substring(task.indexOf('_')+1, task.length)

    $('#' + dayIndex + '_days_duration_tasks_' + taskKey).trigger('change')
}

function updateDayDuration(dayIndex) {
    const inputs = $("input[id^='" + dayIndex + "_days_duration_tasks']");
    let dayDuration = 0
    for (let index = 0; index < inputs.length; index++) {
        const duration = inputs[index].value.substring(0, inputs[index].value.indexOf(' '))
        if (parseFloat(duration)) {
            dayDuration = dayDuration + parseFloat(duration);
        }
    }
    $('#duration_workDays_' + dayIndex ).html(dayDuration);
    updateWeekDuration();
}

function updateWeekDuration() {
    const days = $('span[id^=\'duration_workDays_\']');
    let weekDuration = 0;
    
    for (let index = 0; index < days.length; index++) {
        const duration = parseFloat(days[index].innerHTML);

        if (duration) {
            weekDuration = weekDuration + duration;
        }
    }

    $('#duration_week').html(weekDuration);
}

function increaseValue(e) {
    let select = e.currentTarget.parentNode.previousSibling
    let index = select.selectedIndex;;

    if (index < (select.options.length - 1)) {
        select.selectedIndex = index + 1;
    }

    if (select.options.length == 4 && index == (select.options.length - 1)) {
        select.selectedIndex = 0;
        e.currentTarget.parentNode.parentNode.children[1].selectedIndex ++
    }

    select.dispatchEvent(new Event('change'));
}

function decreaseValue(e) {
    let select = e.currentTarget.parentNode.previousSibling
    let index = select.selectedIndex;

    if (index > 0) {
        select.selectedIndex = index - 1;
    }

    if (select.options.length == 4 && index == 0) {
        select.selectedIndex = select.options.length - 1;
        e.currentTarget.parentNode.parentNode.children[1].selectedIndex --
    }

    select.dispatchEvent(new Event('change'));
}