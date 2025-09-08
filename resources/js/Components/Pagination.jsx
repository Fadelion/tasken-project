import React from 'react';
import { Link } from '@inertiajs/react';

export default function Pagination({ links }) {
    if (links.length <= 3) {
        return null;
    }

    return (
        <div className="flex justify-center mt-4">
            <div className="flex rounded-md">
                {links.map((link, key) => (
                    <React.Fragment key={key}>
                        {link.url === null ? (
                            <div
                                className="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
                                dangerouslySetInnerHTML={{ __html: link.label }}
                            />
                        ) : (
                            <Link
                                className={`mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500 ${link.active ? 'bg-white' : ''}`}
                                href={link.url}
                                dangerouslySetInnerHTML={{ __html: link.label }}
                            />
                        )}
                    </React.Fragment>
                ))}
            </div>
        </div>
    );
}
