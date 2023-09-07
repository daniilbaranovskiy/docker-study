import dayjs from "dayjs";

function formatDate(date) {
    const formattedDate = dayjs(date);

    return formattedDate.format("YYYY-MM-DD");
}
export default formatDate;