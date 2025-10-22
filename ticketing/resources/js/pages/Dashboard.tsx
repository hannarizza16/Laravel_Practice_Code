import { Head, Link } from '@inertiajs/react';
import axios from 'axios';
import { useEffect, useState } from 'react';
import AddForm from '@/components/addForm';
import EditForm from '@/components/editForm';

import ucwords from '@/helpers/format';

export default function Dashboard() {

  const [showForm, setShowForm] = useState(false);

  const [tickets, setTickets] = useState<Ticket[]>([]); // state to hold tickets
  const columns = [
    'case number', 'case title', 'case type', 'petitioner', 'respondent', 'filing date', 'action'
  ]

  type CaseType = 'Civil' | 'Criminal' | 'Constitutional';


  // Ticket type
  type Ticket = {
    ticket_id: number;
    case_number: string;
    case_title: string;
    case_type: CaseType;
    petitioner_name: string;
    respondent_name: string;
    filing_date: string;
  };

////////////////////
// fetching all the 
// tickets in first 
// render or load of 
// the page
///////////////////
  useEffect(() => {
    const allTickets = async () => {
      try {
        const response = await axios.get('/api/tickets');
        setTickets(response.data.data);

      } catch (error) {
        console.error('Error fetching tickets:', error);
      }
    }

    allTickets();
  }, [])


  /////////////////
  // Delete
  /////////////////
  const handleDelete = async (ticket: Ticket) => {

    const confirmed = confirm(`Are you sure you want to delete this ${ticket.case_number} ?`);

    if (!confirmed) return;

    try {
      // Send delete request to the backend
      await axios.delete(`/api/tickets/${ticket.ticket_id}`);

      // Update local state to remove the deleted ticket
      setTickets((prev) =>
        prev.filter((t) => t.ticket_id !== ticket.ticket_id)
      );

      console.log(`Deleted case ${ticket.ticket_id}`);
    } catch (error) {
      console.error('Error deleting ticket:', error);
      alert('Failed to delete ticket. Please try again.');
    }
  };

  //////////////////////////
  // Handle showing Form 
  //////////////////////////
  const handleShowForm = () => {
    setShowForm(true);
  }


  //////////////////////////
  // Handle hiding Add Form 
  //////////////////////////
  const handleCloseForm = () => {
    setShowForm(false);
  }


  const handleAddTicket = (newTicket: Ticket) => {
    setTickets((prev) => [newTicket, ...prev]);
  };

  return (
    <>
      <div className="px-4 sm:px-6 lg:px-8">
        <div className="sm:flex sm:items-center">

          <div className="sm:flex-auto">
            <h1 className="text-base font-semibold text-gray-900">Tickets</h1>
            <p className="mt-2 text-sm text-gray-700">
              A list of all the cases
            </p>
          </div>
          {/* Add new button */}
          <div className="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <button type="button" className="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
              onClick={handleShowForm}>
              Add new cases
            </button>
            {/* add form */}
            {showForm && (
              <div className="fixed inset-0 z-50 flex items-center justify-center bg-white/50 backdrop-blur-sm" onClick={handleCloseForm}>
                <div className=" bg-white rounded-lg shadow-lg p-6 w-full max-w-lg relative max-h-[90vh] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-transparent" onClick={(e) => e.stopPropagation()}>

                  <button
                    onClick={handleCloseForm}
                    className="absolute top-2 right-2 text-gray-400 hover:text-gray-600 text-xl font-bold mouse-pointer">
                    x 
                  </button>
                  {/* passing of props */}
                  <AddForm onClose={handleCloseForm} onAdd={handleAddTicket} />
                </div>
              </div>
            )}
          </div>

        </div>
        <div className="mt-8 flow-root">
          <div className="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div className="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
              <table className="relative min-w-full divide-y divide-gray-300">
                <thead>
                  <tr>
                    {columns.map((label) =>
                      <th key={label} scope="col" className="py-3.5 pr-3 pl-4 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                        {ucwords(label)}
                      </th>
                    )}
                    <th scope="col" className="py-3.5 pr-4 pl-3 sm:pr-0">
                      <span className="sr-only">Edit</span>
                    </th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-gray-200">
                  {tickets.map((ticket) => (
                    <tr key={ticket.ticket_id}>
                      <td className="py-4 pr-3 pl-4 text-sm font-medium whitespace-nowrap text-gray-900 sm:pl-0">{ticket.case_number}</td>
                      <td className="py-4 text-sm whitespace-nowrap text-gray-500">{ucwords(ticket.case_title)}</td>
                      <td className="py-4 text-sm whitespace-nowrap text-gray-500">{ucwords(ticket.case_type)}</td>
                      <td className="py-4 text-sm whitespace-nowrap text-gray-500">{ticket.petitioner_name}</td>
                      <td className="py-4 text-sm whitespace-nowrap text-gray-500">{ticket.respondent_name}</td>
                      <td className="py-4 text-sm whitespace-nowrap text-gray-500">{ticket.filing_date}</td>

                      <td className="py-4  text-sm font-medium whitespace-nowrap sm:pr-0">
                        <div  className='flex space-x-3'>
                          <div>
                            <button className="text-indigo-600 hover:text-indigo-900 mouse-pointer"
                              onClick={handleShowForm}>
                              Edit<span className="sr-only">, {ticket.case_title}</span>
                            </button>

                            {/* edit form */}
                            {/* {showForm && (
                                  <div className="fixed inset-0 z-50 flex items-center justify-center bg-white/50 backdrop-blur-sm" onClick={handleCloseForm}>
                                    <div className=" bg-white rounded-lg shadow-lg p-6 w-full max-w-lg relative max-h-[90vh] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-transparent" onClick={(e) => e.stopPropagation()}>

                                      <button
                                        onClick={handleCloseForm}
                                        className="absolute top-2 right-2 text-gray-400 hover:text-gray-600 text-xl font-bold mouse-pointer">
                                        x 
                                      </button>
                                      {/* passing of props */}
                                                        {/* <EditForm />

                                    </div>
                                  </div>
                                )} */}
                            </div>

                          <button className=" text-red-600 hover:text-red-900"
                            onClick={() => handleDelete(ticket)}>
                            Delete<span className="sr-only">, {ticket.case_title}</span>
                          </button>
                        </div>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </>
  );
}
