import dayjs from 'dayjs';

function formatDate(timestampInMilliseconds) {
    const formattedDate = dayjs(timestampInMilliseconds);

    return formattedDate.format('YYYY-MM-DD HH:mm');
}

export default formatDate;