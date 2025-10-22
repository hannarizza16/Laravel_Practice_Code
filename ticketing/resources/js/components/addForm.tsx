import { PhotoIcon, UserCircleIcon } from '@heroicons/react/24/solid'
import { ChevronDownIcon } from '@heroicons/react/16/solid'
import ucwords from '@/helpers/format';
import { FormEvent, useEffect, useState } from 'react';
import axios from 'axios';


// declare Ticket type need to kasi sa typescript
type Ticket = {
    ticket_id: number;
    case_number: string;
    case_title: string;
    case_type: 'Civil' | 'Criminal' | 'Constitutional';
    petitioner_name: string;
    respondent_name: string;
    filing_date: string;
}

type AddFormProps = {
    onClose: () => void;
    onAdd: (newTicket: Ticket) => void;
};

const caseTypes = [
    'civil','criminal','constitutional'
]

const AddForm = ({ onClose, onAdd}: AddFormProps ) => {

    const initialFormState = {
        case_number: '',
        case_title: '',
        case_type: '',
        petitioner_name: '',
        respondent_name: '',
        filing_date: '',
    }

const [form, setForm] = useState(initialFormState);

    const handleAddNewTicket = async (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        try {
            const response = await axios.post('/api/tickets', form)
            const newTicket = response.data.data ;
            onAdd(newTicket); // notify parent component
            setForm(initialFormState); // reset form
            // onClose(); // close the form after successful addition
            console.log('Ticket added successfully:', newTicket); 

        } catch (error) {
           if (axios.isAxiosError(error) && error.response) {
            const errorData = error.response.data;

            // If your backend sends errors in { errors: {...} }
            if (errorData.errors) {
                console.error('Validation errors:', errorData.errors);
                alert('Validation errors: ' + JSON.stringify(errorData.errors));
            } else {
                // Fallback
                console.error('Unexpected error format:', errorData);
                alert('An error occurred: ' + JSON.stringify(errorData));
            }
            } else {
                console.error('Error adding ticket:', error);
                alert('Unexpected error occurred');
            };
        }
        
    }

    const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {

        const { name, value} = e.target;
        setForm((prevForm) => ({
            ...prevForm,
            // attribute name and its value - nit got from the input field
            [name]: value,
        }))
    }




return(
    <>
    <form onSubmit={handleAddNewTicket}>
      <div className="space-y-12">
        <div className="border-b border-gray-900/10 pb-12">
          <h2 className="text-base/7 font-semibold text-gray-900">Add new case</h2>

          <div className="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
            <div className="sm:col-span-4">
              <label htmlFor="case_number" className="block text-sm/6 font-medium text-gray-900">
                Case Number
              </label>
              <div className="mt-2">
                <div className="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                  <input
                    id="case_number"
                    name="case_number"
                    type="text"
                    value={form.case_number}
                    onChange={handleChange}
                    placeholder="eg. SC_123"
                    className="block min-w-0 grow bg-white py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6"
                  />
                </div>
              </div>
            </div>
            <div className="sm:col-span-4">
              <label htmlFor="case_title" className="block text-sm/6 font-medium text-gray-900">
                Case Title
              </label>
              <div className="mt-2">
                <input id="case_title"
                  name="case_title"
                  type="case_title"
                  value={form.case_title}
                  onChange={handleChange}
                  autoComplete="case_title"
                  className="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                />
              </div>
            </div>

            <div className="sm:col-span-3">
              <label htmlFor="case_type" className="block text-sm/6 font-medium text-gray-900">
                Case Type
              </label>
              <div className="mt-2 grid grid-cols-1">
                <select
                  id="case_type"
                  name="case_type"
                  value={form.case_type}
                  onChange={handleChange}
                  className="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pr-8 pl-3 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                >
                    {caseTypes.map((type)=> 
                        <option key={type} value={type}> {ucwords(type)} </option>
                    )}

                </select>
                <ChevronDownIcon
                  aria-hidden="true"
                  className="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4"
                />
              </div>
            </div>

            <div className="sm:col-span-4">
              <label htmlFor="petitioner_name" className="block text-sm/6 font-medium text-gray-900">
                Petitioner Name
              </label>
              <div className="mt-2">
                <input id="petitioner_name"
                  name="petitioner_name"
                  type="petitioner_name"
                  value={form.petitioner_name}
                  onChange={handleChange}
                  className="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                />
              </div>

            </div>

            <div className="sm:col-span-4">
              <label htmlFor="respondent_name" className="block text-sm/6 font-medium text-gray-900">
                Respondent Name
              </label>
              <div className="mt-2">
                <input id="respondent_name"
                  name="respondent_name"
                  type="respondent_name"
                  value={form.respondent_name}
                  onChange={handleChange}
                  className="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                />
              </div>
            </div>

            <div className="sm:col-span-4">
              <label htmlFor="filing_date" className="block text-sm/6 font-medium text-gray-900">
                Filing Date
              </label>
              <div className="mt-2">
                <input id="filing_date"
                  name="filing_date"
                  type="text"
                  value={form.filing_date}
                  onChange={handleChange}
                  className="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                />
              </div>
            </div>

          </div>
        </div>
      </div>

      <div className="mt-6 flex items-center justify-end gap-x-6">
        <button type="button" className="text-sm/6 font-semibold text-gray-900" onClick={onClose}>
          Cancel
        </button>
        <button
          type="submit"
          className="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
        >
          Save
        </button>
      </div>
    </form>
</>
    )
}

export default AddForm;