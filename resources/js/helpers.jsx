export const formatMessageDateLong = (date) => {
    const now = new Date();

    const inputDate = new Date(date);

    if (isToday(inputDate)) {
        return inputDate.toLocaleTimeString([], {
            hour: "2-digit",
            minute: "2-digit",
        });
    } else if (isYesterday(inputDate)) {
        return (
            "Yesterday " +
            inputDate.toLocaleTimeString([], {
                hour: "2-digit",
                minute: "2-digit",
            })
        );
    } else if (inputDate.getFullYear() === now.getFullYear()) {
        return inputDate.toLocaleDateString([], {
            day: "2-digit",
            month: "short",
            // month: "short",
            // day: "numeric",
            // hour: "2-digit",
            // minute: "2-digit",
        });
    } else {
        return inputDate.toLocaleDateString();
    }
};

export const formatMessageDateShort = ({ date }) => {
    const now = new Date();

    const inputDate = new Date(date);

    if (isToday(inputDate)) {
        return inputDate.toLocaleTimeString([], {
            hour: "2-digit",
            minute: "2-digit",
        });
    } else if (isYesterday(inputDate)) {
        return "Yesterday ";
    } else if (inputDate.getFullYear() === now.getFullYear()) {
        return inputDate.toLocaleDateString([], {
            day: "2-digit",
            month: "short",
        });
    }else {
        return inputDate.toLocaleDateString();
    }
};

export const isToday = (date) => {
    const toDay = new Date();
    return (
        date.getDate() === toDay.getDate() &&
        date.getMonth() === toDay.getMonth() &&
        date.getFullYear() === toDay.getFullYear()
    );
}

export const isYesterday = (date) => {
    const yesterday = new Date();
    yesterday.setDate(yesterday.getDate() - 1);
    return (
        date.getDate() === yesterday.getDate() &&
        date.getMonth() === yesterday.getMonth() &&
        date.getFullYear() === yesterday.getFullYear()
    );
}