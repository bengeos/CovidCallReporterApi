<?php

namespace App\Http\Controllers\ContactGroups;

use App\Http\Controllers\Controller;
use App\Libs\Repositories\ContactGroupsRepository;
use App\Libs\Repositories\ContactsRepository;
use App\Libs\Repositories\GroupedContactsRepository;
use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\GroupedContact;
use Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Validator;

class GroupedContactsController extends Controller
{
    protected $contactGroupsRepo;
    protected $groupedContactRepo;
    protected $contactsRepo;

    /**
     * GroupedContactsController constructor.
     * @param GroupedContactsRepository $groupedContactsRepository
     * @param ContactsRepository $contactsRepository
     * @param ContactGroupsRepository $contactGroupsRepository
     */
    public function __construct(GroupedContactsRepository $groupedContactsRepository, ContactsRepository $contactsRepository, ContactGroupsRepository $contactGroupsRepository)
    {
        $this->middleware('auth:api');
        $this->contactsRepo = $contactsRepository;
        $this->groupedContactRepo = $groupedContactsRepository;
        $this->contactGroupsRepo = $contactGroupsRepository;
    }

    public function getGroupedContacts($group_id)
    {
        try {
            $PAGINATE_NUM = request()->input('PAGINATE_SIZE') ? request()->input('PAGINATE_SIZE') : 10;
            $this->authorize('view', new ContactGroup());
            $query = array();
            $query['contact_group_id'] = $group_id;
            $groupedContacts = $this->groupedContactRepo->getAllPaginated($PAGINATE_NUM, $query);
            return response()->json(['status' => true, 'message' => 'grouped-contacts fetched successfully', 'result' => $groupedContacts, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function createContactGroup()
    {
        try {
            $this->authorize('create', new ContactGroup());
            $thisUser = Auth::guard('api')->user();
            $credential = request()->all();
            $rule = ['contact_group_id' => 'required', 'full_name' => 'required', 'phone' => 'required'];
            $validator = Validator::make($credential, $rule);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'message' => 'please provide necessary information', 'result' => null, 'error' => $error], 500);
            }
            $contactGroupQuery = array();
            $contactGroupQuery['created_by'] = $thisUser->id;
            $contactGroup = $this->contactGroupsRepo->getItem($credential['contact_group_id'], $contactGroupQuery);
            if ($contactGroup instanceof ContactGroup) {
                $oldGroupedContact = GroupedContact::with('contact', 'contact_group')->where('contact_group_id', '=', $credential['contact_group_id'])->whereIn('contact_id', Contact::where('phone', '=', $credential['phone'])->select('id')->get())->first();
                if ($oldGroupedContact instanceof GroupedContact) {
                    return response()->json(['status' => true, 'message' => 'contact-group already found ', 'result' => $oldGroupedContact, 'error' => null], 200);
                } else {
                    $newContact = $this->contactsRepo->addNew($credential);
                    if ($newContact instanceof Contact) {
                        $newGroupedContactData = array();
                        $newGroupedContactData['contact_id'] = $newContact->id;
                        $newGroupedContactData['contact_group_id'] = $contactGroup->id;
                        $newGroupedContact = $this->groupedContactRepo->addNew($newGroupedContactData);
                        if ($newGroupedContact instanceof GroupedContact) {
                            $groupedContact = $this->groupedContactRepo->getItem($newGroupedContact->id);
                            return response()->json(['status' => true, 'message' => 'contact-group created successfully', 'result' => $groupedContact, 'error' => null], 200);
                        } else {
                            return response()->json(['status' => false, 'message' => 'whoops! unable to create grouped-contact', 'result' => null, 'error' => 'unable to create grouped-contact'], 500);
                        }
                    } else {
                        return response()->json(['status' => false, 'message' => 'whoops! something went wrong! try again', 'result' => null, 'error' => 'something went wrong! try again'], 500);
                    }
                }
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! invalid contact-group-id used', 'result' => null, 'error' => 'invalid contact-group-id used'], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function updateGroupedContact()
    {
        try {
            $this->authorize('update', new ContactGroup());
            $credential = request()->only('id', 'contact_group_id', 'full_name', 'phone', 'email');
            $rule = ['id' => 'required'];
            $validator = Validator::make($credential, $rule);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'message' => 'please provide necessary information', 'result' => null, 'error' => $error], 500);
            }
            $selectedGroupedContact = $this->groupedContactRepo->getItem($credential['id']);
            if ($selectedGroupedContact instanceof GroupedContact) {
                $selectedGroupedContact->contact_group_id = isset($credential['contact_group_id']) ? $credential['contact_group_id'] : $selectedGroupedContact->contact_group_id;
                $contact = $this->contactsRepo->getItem($selectedGroupedContact->contact_id);
                if ($contact instanceof Contact) {
                    $contact->full_name = isset($credential['full_name']) ? $credential['full_name'] : $contact->full_name;
                    $contact->email = isset($credential['email']) ? $credential['email'] : $contact->email;
                    $contact->phone = isset($credential['phone']) ? $credential['phone'] : $contact->phone;

                    $contact->update();
                }
                if ($selectedGroupedContact->update()) {
                    $selectedGroupedContact = $this->groupedContactRepo->getItem($selectedGroupedContact->id);
                    return response()->json(['status' => true, 'message' => 'contact-group updated successfully', 'result' => $selectedGroupedContact, 'error' => null], 200);
                } else {
                    return response()->json(['status' => false, 'message' => 'whoops! something went wrong! try again', 'result' => null, 'error' => null], 500);
                }
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! something went wrong! try again', 'result' => null, 'error' => null], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function deleteGroupedContact($id)
    {
        try {
            $this->authorize('delete', new ContactGroup());
            $status = $this->groupedContactRepo->deleteItem($id);
            if ($status) {
                return response()->json(['status' => true, 'message' => 'contact-group deleted successfully', 'result' => null, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! unable to delete this contact-group', 'result' => null, 'error' => 'failed to delete the call-report'], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Throwable $e) {
        }
    }
}
