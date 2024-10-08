import { Link } from "@inertiajs/react";
import UserAvatar from "./UserAvatar";
import GroupAvatar from "./GroupAvatar";
import { ArrowLeftIcon } from "@heroicons/react/24/solid";

import React from "react";

const ConversationHeader = ({ selectedConversation }) => {


    return (
        <>
            {selectedConversation && (
                <div className="flex items-center justify-between p-3 border-b border-gray-700">
                    <div className="flex items-center gap-3">
                        <Link
                            href={route("dashboard")}
                            className="inline-block sm:hidden"
                        >
                            <ArrowLeftIcon className="w-6" />
                        </Link>

                        {selectedConversation.is_user && (
                            <UserAvatar user={selectedConversation} />
                        )}

                        {selectedConversation.is_group && (
                            <GroupAvatar />
                        )}

                        <div>
                            <h3>{selectedConversation.name}</h3>
                            {selectedConversation.is_group && (
                                <p className="text-xs text-gray-500">
                                    {selectedConversation.users.length} members
                                </p>
                            )}
                        </div>
                    </div>
                </div>
            )}
        </>
    );
};

export default ConversationHeader;
