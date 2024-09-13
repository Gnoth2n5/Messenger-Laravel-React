import { usePage } from "@inertiajs/react";
import ReactMarkdown from "react-markdown";
import UserAvatar from "./UserAvatar";
import GroupAvatar from "./GroupAvatar";
import { formatMessageDateLong } from "@/helpers";

import React from "react";

const MessageItem = ({ message }) => {
    const currentUser = usePage().props.auth.user;

    return (
        <div
            className={
                "chat " +
                (message.sender_id === currentUser.id
                    ? "chat-end"
                    : "chat-start")
            }
        >

            <div className="chat-header mt-5">
                {message.sender_id !== currentUser.id
                    ? message.sender.name
                    : ""}
                <time className="text-xs text-gray-500 ml-2 opacity-50">
                    {formatMessageDateLong(message.created_at)}
                </time>
            </div>

            {<UserAvatar user={message.sender} />}

            <div
                className={
                    "chat-bubble" +
                    (message.sender_id === currentUser.id
                        ? " chat-bubble-info"
                        : "")
                }
            >
                <div className="chat-message">
                    <div className="chat-message-content">
                        <ReactMarkdown>{message.message}</ReactMarkdown>
                    </div>
                </div>
            </div>

        </div>
    );
};

export default MessageItem;
